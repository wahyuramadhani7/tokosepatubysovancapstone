<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\ProductHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    // Cache key generators
    private function getProductCacheKey($id)
    {
        return "product_{$id}";
    }

    private function getUnitCacheKey($productId, $unitCode)
    {
        return "unit_{$productId}_{$unitCode}";
    }

    private function getUserCacheKey($key)
    {
        $userId = Auth::id() ?? 'guest';
        return "user_{$userId}_{$key}";
    }

    // Common method to get brand counts
    private function getBrandCounts($productsQuery, $brandNames)
    {
        return $productsQuery->withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->get()
        ->groupBy(function ($product) use ($brandNames) {
            return $brandNames[$product->id] ?? Str::lower(explode(' ', trim($product->name))[0]);
        })
        ->map(function ($group) use ($brandNames) {
            $brandName = Str::title($brandNames[$group->first()->id] ?? explode(' ', trim($group->first()->name))[0]);
            return [
                'name' => $brandName,
                'count' => $group->sum('product_units_count')
            ];
        })
        ->sortBy('name')
        ->pluck('count', 'name');
    }

    // Common method to cache product data
    private function cacheProduct($product, $brand, $model, $stock)
    {
        Cache::forever($this->getProductCacheKey($product->id), [
            'id' => $product->id,
            'name' => $product->name,
            'brand' => $brand,
            'model' => $model,
            'size' => $product->size,
            'color' => $product->color,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'stock' => $stock,
        ]);
    }

    // Common method to cache unit data
    private function cacheUnit($productId, $unit)
    {
        Cache::put($this->getUnitCacheKey($productId, $unit->unit_code), [
            'product_id' => $productId,
            'unit_code' => $unit->unit_code,
            'qr_code' => $unit->qr_code,
            'is_active' => $unit->is_active,
        ], now()->addDays(7)); // TTL 7 hari untuk unit
    }

    // Common method to log product history
    private function logProductHistory($type, $product, $brand, $model, $stock, $stockChange, $userId, $userName)
    {
        ProductHistory::create([
            'type' => $type,
            'product_id' => $product->id,
            'brand' => $brand,
            'model' => $model,
            'size' => $product->size,
            'color' => $product->color,
            'stock' => $stock,
            'stock_change' => $stockChange,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price ?? null,
            'user_id' => $userId,
            'user_name' => $userName,
            'timestamp' => Carbon::now('Asia/Jakarta'),
        ]);
    }

    // Common method to delete product and related cache
    private function deleteProductAndCache($product)
    {
        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
        $unitCodes = $product->productUnits()->pluck('unit_code')->toArray();
        foreach ($unitCodes as $unitCode) {
            Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
        }
        Cache::forget($this->getProductCacheKey($product->id));
        Cache::forget($this->getUserCacheKey('new_units_' . $product->id));
        $newProducts = array_diff(Cache::get($this->getUserCacheKey('new_products'), []), [$product->id]);
        $updatedProducts = array_diff(Cache::get($this->getUserCacheKey('updated_products'), []), [$product->id]);
        $existingMismatches = Cache::get($this->getUserCacheKey('stock_mismatches'), []);
        unset($existingMismatches[$product->id]);
        unset($brandNames[$product->id]);
        Cache::forever($this->getUserCacheKey('new_products'), $newProducts);
        Cache::forever($this->getUserCacheKey('updated_products'), $updatedProducts);
        Cache::forever($this->getUserCacheKey('stock_mismatches'), $existingMismatches);
        Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);
        $product->productUnits()->delete();
        $product->delete();
    }

    // Common product query for index and search
    private function getProductQuery($searchTerm = '', $sizeTerm = '', $lowStockFilter = 0)
    {
        return Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->whereHas('productUnits', function ($query) use ($lowStockFilter) {
            $query->where('is_active', true);
            if ($lowStockFilter) {
                $query->whereIn('product_id', function ($subQuery) {
                    $subQuery->select('product_id')
                        ->from('product_units')
                        ->where('is_active', true)
                        ->groupBy('product_id')
                        ->havingRaw('COUNT(*) < 5');
                });
            }
        })
        ->when($searchTerm, function ($query) use ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('color', 'like', '%' . $searchTerm . '%');
            });
        })
        ->when($sizeTerm, function ($query) use ($sizeTerm) {
            $query->where('size', 'like', '%' . $sizeTerm . '%');
        })
        ->orderBy('updated_at', 'desc')
        ->orderBy('name')
        ->orderBy('size');
    }

    public function index()
    {
        $products = $this->getProductQuery()->paginate(10);
        $totalProducts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })->count();
        $totalStock = ProductUnit::where('is_active', true)->count();
        $lowStockProducts = ProductUnit::where('is_active', true)
            ->whereIn('product_id', function ($query) {
                $query->select('product_id')
                    ->from('product_units')
                    ->where('is_active', true)
                    ->groupBy('product_id')
                    ->havingRaw('COUNT(*) <= 10');
            })->count();
        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
            return Product::pluck('name', 'id')->map(function ($name) {
                return explode(' ', trim($name))[0];
            })->toArray();
        });
        $brandCounts = $this->getBrandCounts(Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        }), $brandNames);
        $newProducts = Cache::get($this->getUserCacheKey('new_products'), []);
        $updatedProducts = Cache::get($this->getUserCacheKey('updated_products'), []);

        return view('inventory.index', compact(
            'products',
            'totalProducts',
            'lowStockProducts',
            'totalStock',
            'brandCounts',
            'newProducts',
            'updatedProducts'
        ));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $sizeTerm = $request->input('size', '');
        $lowStockFilter = $request->input('low_stock', 0);
        $page = $request->input('page', 1);
        $cacheKey = 'product_search_' . md5($searchTerm . '_' . $sizeTerm . '_' . $lowStockFilter . '_' . $page);

        $products = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($searchTerm, $sizeTerm, $lowStockFilter) {
            return $this->getProductQuery($searchTerm, $sizeTerm, $lowStockFilter)
                ->paginate(10)
                ->appends(['search' => $searchTerm, 'size' => $sizeTerm, 'low_stock' => $lowStockFilter]);
        });

        $totalProducts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })->count();
        $totalStock = ProductUnit::where('is_active', true)->count();
        $lowStockProducts = ProductUnit::where('is_active', true)
            ->whereIn('product_id', function ($query) {
                $query->select('product_id')
                    ->from('product_units')
                    ->where('is_active', true)
                    ->groupBy('product_id')
                    ->havingRaw('COUNT(*) <= 10');
            })->count();
        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
            return Product::pluck('name', 'id')->map(function ($name) {
                return explode(' ', trim($name))[0];
            })->toArray();
        });
        $brandCounts = $this->getBrandCounts($this->getProductQuery($searchTerm, $sizeTerm, $lowStockFilter), $brandNames);
        $newProducts = Cache::get($this->getUserCacheKey('new_products'), []);
        $updatedProducts = Cache::get($this->getUserCacheKey('updated_products'), []);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'products' => collect($products->items())->map(function ($product) use ($brandNames) {
                    $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
                    return [
                        'id' => $product->id,
                        'brand' => $brand,
                        'model' => trim(str_replace($brand, '', $product->name)),
                        'name' => $product->name,
                        'size' => $product->size,
                        'color' => $product->color,
                        'selling_price' => $product->selling_price,
                        'discount_price' => $product->discount_price,
                        'stock' => $product->product_units_count,
                    ];
                })->values(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                    'per_page' => $products->perPage(),
                ],
                'totalProducts' => $totalProducts,
                'totalStock' => $totalStock,
                'lowStockProducts' => $lowStockProducts,
                'brandCounts' => $brandCounts,
                'newProducts' => $newProducts,
                'updatedProducts' => $updatedProducts,
            ], 200);
        }

        return view('inventory.index', compact(
            'products',
            'totalProducts',
            'lowStockProducts',
            'totalStock',
            'searchTerm',
            'sizeTerm',
            'lowStockFilter',
            'brandCounts',
            'newProducts',
            'updatedProducts'
        ));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'sizes' => 'required|array|min:1',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.stock' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:selling_price',
        ]);

        $totalStock = array_sum(array_column($validated['sizes'], 'stock'));
        if ($totalStock == 0) {
            return redirect()->route('inventory.index')->with('success', 'Produk tidak disimpan karena stok 0.');
        }

        DB::beginTransaction();
        try {
            $newUnitCodes = [];
            $newProductIds = [];
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
                return Product::pluck('name', 'id')->map(function ($name) {
                    return explode(' ', trim($name))[0];
                })->toArray();
            });
            $userId = Auth::id() ?? 'guest';
            $userName = Auth::user()->name ?? 'Unknown';

            foreach ($validated['sizes'] as $sizeData) {
                if ($sizeData['stock'] == 0) {
                    continue;
                }

                $product = Product::create([
                    'name' => trim($validated['brand'] . ' ' . $validated['model']),
                    'size' => $sizeData['size'],
                    'color' => $validated['color'],
                    'selling_price' => $validated['selling_price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                ]);

                $newProductIds[] = $product->id;
                $brandNames[$product->id] = $validated['brand'];

                $this->logProductHistory(
                    'masuk',
                    $product,
                    $validated['brand'],
                    $validated['model'],
                    $sizeData['stock'],
                    "+{$sizeData['stock']} unit",
                    $userId,
                    $userName
                );

                $productNewUnitCodes = [];
                for ($i = 0; $i < $sizeData['stock']; $i++) {
                    $unitCode = 'UNIT-' . strtoupper(Str::random(8));
                    $unit = ProductUnit::create([
                        'product_id' => $product->id,
                        'unit_code' => $unitCode,
                        'qr_code' => '',
                        'is_active' => true,
                    ]);
                    $unit->update(['qr_code' => route('inventory.show_unit', ['product' => $product->id, 'unitCode' => $unit->unit_code])]);
                    $this->cacheUnit($product->id, $unit);
                    $newUnitCodes[] = $unit->unit_code;
                    $productNewUnitCodes[] = $unit->unit_code;
                }

                $this->cacheProduct($product, $validated['brand'], $validated['model'], $sizeData['stock']);
                Cache::put($this->getUserCacheKey('new_units_' . $product->id), $productNewUnitCodes, now()->addHour()); // TTL 1 jam
                Log::info("Stored new units for product ID {$product->id}: " . json_encode($productNewUnitCodes));
            }

            Cache::forever($this->getUserCacheKey('new_products'), array_merge(Cache::get($this->getUserCacheKey('new_products'), []), $newProductIds));
            Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Produk dan unit berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store product: ' . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $product = Product::with(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->find($id);

        if (!$product) {
            $cachedProduct = Cache::get($this->getProductCacheKey($id));
            $product = $cachedProduct
                ? (object) array_merge($cachedProduct, ['productUnits' => collect([])])
                : (object) [
                    'id' => $id,
                    'name' => 'Produk Tidak Ditemukan',
                    'size' => 'N/A',
                    'color' => 'N/A',
                    'selling_price' => 0,
                    'discount_price' => null,
                    'stock' => 0,
                    'productUnits' => collect([]),
                ];
        }

        return view('inventory.show', compact('product'));
    }

    public function showUnit($productId, $unitCode)
    {
        $product = Product::with(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->find($productId);
        $unit = ProductUnit::where('product_id', $productId)->where('unit_code', $unitCode)->first();
        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
            return Product::pluck('name', 'id')->map(function ($name) {
                return explode(' ', trim($name))[0];
            })->toArray();
        });
        $similarProducts = [];

        if ($product && $product->name !== 'Produk Tidak Ditemukan') {
            $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
            $similarProducts = Product::withCount(['productUnits' => function ($query) {
                $query->where('is_active', true);
            }])
            ->where('name', 'like', $brand . '%')
            ->where('id', '!=', $productId)
            ->whereHas('productUnits', function ($query) {
                $query->where('is_active', true);
            })
            ->get()
            ->map(function ($similarProduct) use ($brandNames) {
                $brand = $brandNames[$similarProduct->id] ?? explode(' ', trim($similarProduct->name))[0];
                return [
                    'id' => $similarProduct->id,
                    'brand' => $brand,
                    'model' => trim(str_replace($brand, '', $similarProduct->name)),
                    'name' => $similarProduct->name,
                    'size' => $similarProduct->size,
                    'color' => $similarProduct->color,
                    'stock' => $similarProduct->product_units_count,
                    'selling_price' => $similarProduct->selling_price,
                    'discount_price' => $similarProduct->discount_price,
                ];
            });
        }

        if (!$product || !$unit) {
            $cachedProduct = Cache::get($this->getProductCacheKey($productId));
            $cachedUnit = Cache::get($this->getUnitCacheKey($productId, $unitCode));

            if ($cachedProduct && $cachedUnit) {
                $product = (object) $cachedProduct;
                $unit = (object) $cachedUnit;
                $similarProducts = [];
            } else {
                $product = (object) [
                    'id' => $productId,
                    'name' => 'Produk Tidak Ditemukan',
                    'size' => 'N/A',
                    'color' => 'N/A',
                    'selling_price' => 0,
                    'discount_price' => null,
                    'productUnits' => collect([]),
                ];
                $unit = (object) [
                    'unit_code' => $unitCode,
                    'qr_code' => route('inventory.show_unit', ['product' => $productId, 'unitCode' => $unitCode]),
                    'is_active' => false,
                ];
                $similarProducts = [];
            }
        }

        return view('inventory.show_unit', compact('product', 'unit', 'similarProducts'));
    }

    public function json($id)
    {
        $product = Product::with(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->find($id);

        if (!$product) {
            $cachedProduct = Cache::get($this->getProductCacheKey($id));
            if ($cachedProduct) {
                $product = (object) array_merge($cachedProduct, ['productUnits' => collect([])]);
            } else {
                return response()->json(['error' => 'Produk tidak ditemukan'], 404);
            }
        }

        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
            return Product::pluck('name', 'id')->map(function ($name) {
                return explode(' ', trim($name))[0];
            })->toArray();
        });
        $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
        $model = trim(str_replace($brand, '', $product->name));

        return response()->json([
            'id' => $product->id,
            'brand' => $brand,
            'model' => $model,
            'name' => $product->name,
            'color' => $product->color,
            'size' => $product->size,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'stock' => $product->productUnits->count(),
            'units' => $product->productUnits->map(function ($unit) {
                return [
                    'unit_code' => $unit->unit_code,
                    'qr_code' => $unit->qr_code,
                    'is_active' => $unit->is_active,
                ];
            }),
        ], 200);
    }

    public function edit($id)
    {
        $product = Product::with(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->find($id);

        if (!$product) {
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }
        return view('inventory.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'sizes' => 'required|array|min:1',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.stock' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:selling_price',
        ]);

        $totalStock = array_sum(array_column($validated['sizes'], 'stock'));
        if ($totalStock == 0) {
            DB::beginTransaction();
            try {
                $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
                    return Product::pluck('name', 'id')->map(function ($name) {
                        return explode(' ', trim($name))[0];
                    })->toArray();
                });
                $this->logProductHistory(
                    'keluar',
                    $product,
                    $brandNames[$product->id] ?? explode(' ', trim($product->name))[0],
                    trim(str_replace($brandNames[$product->id] ?? explode(' ', trim($product->name))[0], '', $product->name)),
                    0,
                    '0 unit',
                    Auth::id() ?? 'guest',
                    Auth::user()->name ?? 'Unknown'
                );
                $this->deleteProductAndCache($product);
                DB::commit();
                return redirect()->route('inventory.index')->with('success', 'Produk dihapus karena stok 0.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to delete product due to zero stock: ' . $e->getMessage());
                return redirect()->route('inventory.index')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
            }
        }

        DB::beginTransaction();
        try {
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
                return Product::pluck('name', 'id')->map(function ($name) {
                    return explode(' ', trim($name))[0];
                })->toArray();
            });
            $userId = Auth::id() ?? 'guest';
            $userName = Auth::user()->name ?? 'Unknown';
            $currentStock = $product->productUnits()->where('is_active', true)->count();
            $desiredStock = $validated['sizes'][0]['stock'];
            $stockChange = $desiredStock - $currentStock;
            $stockChangeDescription = $stockChange > 0 ? "+{$stockChange} unit" : ($stockChange < 0 ? "-" . abs($stockChange) . " unit" : "tidak berubah");

            $this->logProductHistory(
                'perbarui',
                $product,
                $validated['brand'],
                $validated['model'],
                $desiredStock,
                $stockChangeDescription,
                $userId,
                $userName
            );

            $product->update([
                'name' => trim($validated['brand'] . ' ' . $validated['model']),
                'size' => $validated['sizes'][0]['size'],
                'color' => $validated['color'],
                'selling_price' => $validated['selling_price'],
                'discount_price' => $validated['discount_price'] ?? null,
            ]);

            $brandNames[$product->id] = $validated['brand'];
            $newUnitCodes = Cache::get($this->getUserCacheKey('new_units_' . $product->id), []);
            $newProductIds = [];
            $updatedProductIds = [$product->id];

            if ($desiredStock > $currentStock) {
                for ($i = $currentStock; $i < $desiredStock; $i++) {
                    $unitCode = 'UNIT-' . strtoupper(Str::random(8));
                    $unit = ProductUnit::create([
                        'product_id' => $product->id,
                        'unit_code' => $unitCode,
                        'qr_code' => '',
                        'is_active' => true,
                    ]);
                    $unit->update(['qr_code' => route('inventory.show_unit', ['product' => $product->id, 'unitCode' => $unit->unit_code])]);
                    $this->cacheUnit($product->id, $unit);
                    $newUnitCodes[] = $unit->unit_code;
                }
            } elseif ($desiredStock < $currentStock) {
                $unitsToDeactivate = $product->productUnits()->where('is_active', true)->take($currentStock - $desiredStock)->get();
                foreach ($unitsToDeactivate as $unit) {
                    $unit->update(['is_active' => false]);
                    $this->cacheUnit($product->id, $unit);
                    $newUnitCodes = array_diff($newUnitCodes, [$unit->unit_code]);
                }
            }

            for ($i = 1; $i < count($validated['sizes']); $i++) {
                $sizeData = $validated['sizes'][$i];
                if ($sizeData['stock'] == 0) {
                    continue;
                }
                $newProduct = Product::create([
                    'name' => trim($validated['brand'] . ' ' . $validated['model']),
                    'size' => $sizeData['size'],
                    'color' => $validated['color'],
                    'selling_price' => $validated['selling_price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                ]);

                $newProductIds[] = $newProduct->id;
                $brandNames[$newProduct->id] = $validated['brand'];

                $this->logProductHistory(
                    'masuk',
                    $newProduct,
                    $validated['brand'],
                    $validated['model'],
                    $sizeData['stock'],
                    "+{$sizeData['stock']} unit",
                    $userId,
                    $userName
                );

                $productNewUnitCodes = [];
                for ($j = 0; $j < $sizeData['stock']; $j++) {
                    $unitCode = 'UNIT-' . strtoupper(Str::random(8));
                    $unit = ProductUnit::create([
                        'product_id' => $newProduct->id,
                        'unit_code' => $unitCode,
                        'qr_code' => '',
                        'is_active' => true,
                    ]);
                    $unit->update(['qr_code' => route('inventory.show_unit', ['product' => $newProduct->id, 'unitCode' => $unit->unit_code])]);
                    $this->cacheUnit($newProduct->id, $unit);
                    $productNewUnitCodes[] = $unit->unit_code;
                }

                $this->cacheProduct($newProduct, $validated['brand'], $validated['model'], $sizeData['stock']);
                Cache::put($this->getUserCacheKey('new_units_' . $newProduct->id), $productNewUnitCodes, now()->addHour());
                Log::info("Stored new units for new product ID {$newProduct->id}: " . json_encode($productNewUnitCodes));
            }

            Cache::put($this->getUserCacheKey('new_units_' . $product->id), $newUnitCodes, now()->addHour());
            Cache::forever($this->getUserCacheKey('new_products'), array_merge(Cache::get($this->getUserCacheKey('new_products'), []), $newProductIds));
            Cache::forever($this->getUserCacheKey('updated_products'), array_merge(Cache::get($this->getUserCacheKey('updated_products'), []), $updatedProductIds));
            Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);
            $this->cacheProduct($product, $validated['brand'], $validated['model'], $desiredStock);

            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Produk dan unit berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update product: ' . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
                return Product::pluck('name', 'id')->map(function ($name) {
                    return explode(' ', trim($name))[0];
                })->toArray();
            });
            $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
            $model = trim(str_replace($brand, '', $product->name));
            $stock = $product->productUnits()->where('is_active', true)->count();

            $this->logProductHistory(
                'keluar',
                $product,
                $brand,
                $model,
                $stock,
                "-{$stock} unit",
                Auth::id() ?? 'guest',
                Auth::user()->name ?? 'Unknown'
            );

            $this->deleteProductAndCache($product);
            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Produk dan unit berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete product: ' . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function printQr($id)
    {
        $product = Product::with(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->find($id);

        if (!$product) {
            $cachedProduct = Cache::get($this->getProductCacheKey($id));
            if ($cachedProduct) {
                $product = (object) array_merge($cachedProduct, ['productUnits' => collect([])]);
            } else {
                Log::warning("Product not found for QR printing: ID {$id}");
                return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
            }
        }

        $newUnitCodes = Cache::get($this->getUserCacheKey('new_units_' . $id), []);
        Log::info("Printing QR for product ID {$id}, new unit codes: " . json_encode($newUnitCodes));

        if (!empty($newUnitCodes)) {
            $product->productUnits = $product->productUnits->whereIn('unit_code', $newUnitCodes);
            Log::info("Filtered to new units for product ID {$id}: " . json_encode($product->productUnits->pluck('unit_code')->toArray()));
        } else {
            Log::info("No new units found for product ID {$id}, using all active units: " . json_encode($product->productUnits->pluck('unit_code')->toArray()));
        }

        Cache::forget($this->getUserCacheKey('new_units_' . $id));
        Log::info("Cleared new_units cache for product ID {$id}");

        return view('inventory.print_qr', compact('product', 'newUnitCodes'));
    }

    public function stockOpname()
    {
        $totalStock = ProductUnit::where('is_active', true)->count();
        $reports = Cache::get('stock_opname_reports', []);

        Cache::forget($this->getUserCacheKey('stock_mismatches'));
        Cache::forget($this->getUserCacheKey('new_products'));
        Cache::forget($this->getUserCacheKey('updated_products'));
        Cache::forget($this->getUserCacheKey('brand_names'));
        foreach ($reports as $report) {
            Cache::forget($this->getUserCacheKey('new_units_' . $report['product_id']));
        }

        $previousPhysicalStocks = array_column($reports, 'physical_stock', 'product_id');

        return view('inventory.stock_opname', compact('totalStock', 'reports', 'previousPhysicalStocks'));
    }

    public function updatePhysicalStock(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'physical_stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $currentStock = $product->productUnits()->where('is_active', true)->count();
            $physicalStock = $validated['physical_stock'];
            $difference = $physicalStock - $currentStock;
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
                return Product::pluck('name', 'id')->map(function ($name) {
                    return explode(' ', trim($name))[0];
                })->toArray();
            });
            $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
            $model = trim(str_replace($brand, '', $product->name));
            $stockMismatch = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'brand' => $brand,
                'model' => $model,
                'physical_stock' => $physicalStock,
                'system_stock' => $currentStock,
                'difference' => $difference,
            ];
            $newUnitCodes = Cache::get($this->getUserCacheKey('new_units_' . $product->id), []);

            if ($physicalStock == 0) {
                $this->logProductHistory(
                    'keluar',
                    $product,
                    $brand,
                    $model,
                    0,
                    "-{$currentStock} unit",
                    Auth::id() ?? 'guest',
                    Auth::user()->name ?? 'Unknown'
                );
                $this->deleteProductAndCache($product);
                $stockMismatch['message'] = 'Produk dihapus karena stok fisik 0.';
                $stockMessage = 'Produk dihapus karena stok fisik 0.';
            } elseif ($difference > 0) {
                $stockMessage = "Stok fisik: {$physicalStock}, Stok sistem: {$currentStock}, Selisih: +{$difference} unit";
                $stockMismatch['message'] = "Stok tidak sesuai, lebih {$difference} unit";
                for ($i = $currentStock; $i < $physicalStock; $i++) {
                    do {
                        $unitCode = 'UNIT-' . strtoupper(Str::random(12));
                    } while (ProductUnit::where('unit_code', $unitCode)->exists() || Cache::has($this->getUnitCacheKey($product->id, $unitCode)));
                    $unit = ProductUnit::create([
                        'product_id' => $product->id,
                        'unit_code' => $unitCode,
                        'qr_code' => '',
                        'is_active' => true,
                    ]);
                    $unit->update(['qr_code' => route('inventory.show_unit', ['product' => $product->id, 'unitCode' => $unit->unit_code])]);
                    $newUnitCodes[] = $unit->unit_code;
                    $this->cacheUnit($product->id, $unit);
                    $this->logProductHistory(
                        'masuk',
                        $product,
                        $brand,
                        $model,
                        $physicalStock,
                        '+1 unit',
                        Auth::id() ?? 'guest',
                        Auth::user()->name ?? 'Unknown'
                    );
                }
            } elseif ($difference < 0) {
                $stockMessage = "Stok fisik: {$physicalStock}, Stok sistem: {$currentStock}, Selisih: -" . abs($difference) . " unit";
                $stockMismatch['message'] = "Stok tidak sesuai, kurang " . abs($difference) . " unit";
                $unitsToDeactivate = $product->productUnits()->where('is_active', true)->take(abs($difference))->get();
                foreach ($unitsToDeactivate as $unit) {
                    $unit->update(['is_active' => false]);
                    $this->cacheUnit($product->id, $unit);
                    $newUnitCodes = array_diff($newUnitCodes, [$unit->unit_code]);
                    $this->logProductHistory(
                        'keluar',
                        $product,
                        $brand,
                        $model,
                        $physicalStock,
                        '-1 unit',
                        Auth::id() ?? 'guest',
                        Auth::user()->name ?? 'Unknown'
                    );
                }
            } else {
                $stockMessage = "Stok fisik sesuai dengan stok sistem: {$physicalStock} unit";
                $stockMismatch['message'] = "Stok sesuai dengan sistem";
            }

            $existingMismatches = Cache::get($this->getUserCacheKey('stock_mismatches'), []);
            $existingMismatches[$product->id] = $stockMismatch;
            Cache::forever($this->getUserCacheKey('stock_mismatches'), $existingMismatches);
            Cache::put($this->getUserCacheKey('new_units_' . $product->id), $newUnitCodes, now()->addHour());
            $this->cacheProduct($product, $brand, $model, $physicalStock);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $stockMessage,
                'stock_message' => $stockMessage,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'brand' => $brand,
                    'model' => $model,
                    'physical_stock' => $physicalStock,
                    'system_stock' => $currentStock,
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update physical stock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat stok fisik: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveReport(Request $request)
    {
        $validated = $request->validate([
            'reports' => 'required|array',
            'reports.*.product_id' => 'required|integer',
            'reports.*.name' => 'nullable|string',
            'reports.*.size' => 'nullable|string',
            'reports.*.color' => 'nullable|string',
            'reports.*.system_stock' => 'required|integer',
            'reports.*.physical_stock' => 'required|integer',
            'reports.*.difference' => 'required|integer',
            'reports.*.scanned_qr_codes' => 'nullable|array',
            'reports.*.scanned_qr_codes.*' => 'string',
        ]);

        try {
            $reports = Cache::get('stock_opname_reports', []);
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
                return Product::pluck('name', 'id')->map(function ($name) {
                    return explode(' ', trim($name))[0];
                })->toArray();
            });
            $existingReports = array_column($reports, null, 'product_id');

            foreach ($validated['reports'] as $report) {
                $product = Product::find($report['product_id']);
                $scannedQRCodes = $report['scanned_qr_codes'] ?? [];
                $allUnitCodes = $product ? ProductUnit::where('product_id', $report['product_id'])
                    ->where('is_active', true)
                    ->pluck('unit_code')
                    ->toArray() : [];
                $unscannedQRCodes = array_diff($allUnitCodes, array_map(function($qr) {
                    return basename(parse_url($qr, PHP_URL_PATH));
                }, $scannedQRCodes));

                if ($report['physical_stock'] == 0 && $product) {
                    DB::beginTransaction();
                    try {
                        $this->logProductHistory(
                            'keluar',
                            $product,
                            $brandNames[$product->id] ?? explode(' ', trim($product->name))[0],
                            trim(str_replace($brandNames[$product->id] ?? explode(' ', trim($product->name))[0], '', $product->name)),
                            0,
                            "-{$report['system_stock']} unit",
                            Auth::id() ?? 'guest',
                            Auth::user()->name ?? 'Unknown'
                        );
                        $this->deleteProductAndCache($product);
                        DB::commit();
                        unset($existingReports[$report['product_id']]);
                        continue;
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Failed to delete product during stock opname: ' . $e->getMessage());
                        continue;
                    }
                }

                $brand = $brandNames[$report['product_id']] ?? ($report['name'] ? explode(' ', trim($report['name']))[0] : 'Unknown');
                $model = $report['name'] ? trim(str_replace($brand, '', $report['name'])) : 'Unknown';
                $existingReports[$report['product_id']] = [
                    'product_id' => $report['product_id'],
                    'name' => $report['name'] ?? 'Produk Tidak Diketahui',
                    'brand' => $brand,
                    'model' => $model,
                    'size' => $report['size'] ?? '-',
                    'color' => $report['color'] ?? '-',
                    'system_stock' => $report['system_stock'],
                    'physical_stock' => $report['physical_stock'],
                    'difference' => $report['difference'],
                    'scanned_qr_codes' => $scannedQRCodes,
                    'unscanned_qr_codes' => array_values($unscannedQRCodes),
                    'timestamp' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                ];
            }

            Cache::put('stock_opname_reports', array_values($existingReports), now()->addHours(24)); // TTL 24 jam
            return response()->json([
                'success' => true,
                'message' => 'Laporan stock opname berhasil disimpan',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to save stock opname report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan laporan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function status()
    {
        $newProducts = Cache::get($this->getUserCacheKey('new_products'), []);
        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), function () {
            return Product::pluck('name', 'id')->map(function ($name) {
                return explode(' ', trim($name))[0];
            })->toArray();
        });

        return view('inventory.product-status', compact('newProducts', 'brandNames'));
    }

    public function deleteReport($index)
    {
        try {
            $reports = Cache::get('stock_opname_reports', []);
            if (isset($reports[$index])) {
                unset($reports[$index]);
                Cache::put('stock_opname_reports', array_values($reports), now()->addHours(24)); // TTL 24 jam
                return redirect()->route('inventory.stock_opname')->with('success', 'Laporan berhasil dihapus');
            }
            return redirect()->route('inventory.stock_opname')->with('error', 'Laporan tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Failed to delete stock opname report: ' . $e->getMessage());
            return redirect()->route('inventory.stock_opname')->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    public function deleteAllReports()
    {
        try {
            Cache::forget('stock_opname_reports');
            Cache::forget($this->getUserCacheKey('stock_mismatches'));
            Cache::forget($this->getUserCacheKey('new_products'));
            Cache::forget($this->getUserCacheKey('updated_products'));
            Cache::forget($this->getUserCacheKey('brand_names'));
            return redirect()->route('inventory.stock_opname')->with('success', 'Semua laporan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Failed to delete all stock opname reports: ' . $e->getMessage());
            return redirect()->route('inventory.stock_opname')->with('error', 'Gagal menghapus semua laporan: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $time_filter = $request->input('time_filter', 'all');
        $action_filter = $request->input('action_filter', 'all');
        $search = $request->input('search', '');

        $query = ProductHistory::query()
            ->when($time_filter === 'weekly', function ($q) {
                $q->where('timestamp', '>=', Carbon::now('Asia/Jakarta')->startOfWeek());
            })
            ->when($time_filter === 'monthly', function ($q) {
                $q->where('timestamp', '>=', Carbon::now('Asia/Jakarta')->startOfMonth());
            })
            ->when($action_filter !== 'all', function ($q) use ($action_filter) {
                $typeMap = [
                    'added' => 'masuk',
                    'edited' => 'perbarui',
                    'deleted' => 'keluar'
                ];
                $q->where('type', $typeMap[$action_filter] ?? $action_filter);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('size', 'like', "%{$search}%")
                        ->orWhere('color', 'like', "%{$search}%")
                        ->orWhere('user_name', 'like', "%{$search}%");
                });
            })
            ->latest('timestamp');

        $filteredHistory = $query->get();
        return view('inventory.history', compact('filteredHistory', 'time_filter', 'action_filter', 'search'));
    }
}