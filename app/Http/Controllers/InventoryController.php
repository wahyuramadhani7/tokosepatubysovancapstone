<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\ProductHistory;
use App\Models\StockOpnameReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    // Cache keys
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

    public function index()
    {
        $products = Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })
        ->orderBy('updated_at', 'desc')
        ->orderBy('name')
        ->orderBy('size')
        ->paginate(10);

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

        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
        $brandCounts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })
        ->withCount(['productUnits' => function ($query) {
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

        $newProducts = Cache::get($this->getUserCacheKey('new_products'), []);
        $updatedProducts = Cache::get($this->getUserCacheKey('updated_products'), []);

        return view('inventory.index', compact('products', 'totalProducts', 'lowStockProducts', 'totalStock', 'brandCounts', 'newProducts', 'updatedProducts'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $sizeTerm = $request->input('size', '');
        $page = $request->input('page', 1);
        $cacheKey = 'product_search_' . md5($searchTerm . '_' . $sizeTerm . '_' . $page);

        $products = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($searchTerm, $sizeTerm) {
            return Product::withCount(['productUnits' => function ($query) {
                $query->where('is_active', true);
            }])
            ->whereHas('productUnits', function ($query) {
                $query->where('is_active', true);
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
            ->orderBy('size')
            ->paginate(10)
            ->appends(['search' => $searchTerm, 'size' => $sizeTerm]);
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

        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
        $brandCounts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
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
        ->withCount(['productUnits' => function ($query) {
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

        $newProducts = Cache::get($this->getUserCacheKey('new_products'), []);
        $updatedProducts = Cache::get($this->getUserCacheKey('updated_products'), []);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'products' => collect($products->items())->map(function ($product) use ($brandNames) {
                    $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
                    $model = trim(str_replace($brand, '', $product->name));
                    return [
                        'id' => $product->id,
                        'brand' => $brand,
                        'model' => $model,
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

        return view('inventory.index', compact('products', 'totalProducts', 'lowStockProducts', 'totalStock', 'searchTerm', 'sizeTerm', 'brandCounts', 'newProducts', 'updatedProducts'));
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
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
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

                ProductHistory::create([
                    'type' => 'masuk',
                    'product_id' => $product->id,
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'size' => $sizeData['size'],
                    'color' => $validated['color'],
                    'stock' => $sizeData['stock'],
                    'stock_change' => "+{$sizeData['stock']} unit",
                    'selling_price' => $validated['selling_price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'timestamp' => Carbon::now('Asia/Jakarta'),
                ]);

                $units = [];
                $productNewUnitCodes = [];
                for ($i = 0; $i < $sizeData['stock']; $i++) {
                    $unitCode = 'UNIT-' . strtoupper(Str::random(8));
                    $unit = ProductUnit::create([
                        'product_id' => $product->id,
                        'unit_code' => $unitCode,
                        'qr_code' => '',
                        'is_active' => true,
                    ]);
                    $qrCode = route('inventory.show_unit', ['product' => $product->id, 'unitCode' => $unit->unit_code]);
                    $unit->update(['qr_code' => $qrCode]);
                    $units[] = $unit;
                    $newUnitCodes[] = $unit->unit_code;
                    $productNewUnitCodes[] = $unit->unit_code;
                }

                Cache::forever($this->getProductCacheKey($product->id), [
                    'id' => $product->id,
                    'name' => $product->name,
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'size' => $product->size,
                    'color' => $product->color,
                    'selling_price' => $product->selling_price,
                    'discount_price' => $product->discount_price,
                    'stock' => $sizeData['stock'],
                ]);

                foreach ($units as $unit) {
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $unit->qr_code,
                        'is_active' => true,
                    ]);
                }

                Cache::forever($this->getUserCacheKey('new_units_' . $product->id), $productNewUnitCodes);
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
            if ($cachedProduct) {
                $product = (object) $cachedProduct;
                $product->productUnits = collect([]);
            } else {
                $product = (object) [
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
        }

        return view('inventory.show', compact('product'));
    }

    public function showUnit($productId, $unitCode)
    {
        $product = Product::with(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->find($productId);
        $unit = ProductUnit::where('product_id', $productId)->where('unit_code', $unitCode)->first();

        $similarProducts = [];
        if ($product && $product->name !== 'Produk Tidak Ditemukan') {
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
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
                $model = trim(str_replace($brand, '', $similarProduct->name));
                return [
                    'id' => $similarProduct->id,
                    'brand' => $brand,
                    'model' => $model,
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
                $product = (object) $cachedProduct;
                $product->productUnits = collect([]);
            } else {
                return response()->json(['error' => 'Produk tidak ditemukan'], 404);
            }
        }

        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
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
                $product->productUnits()->delete();
                $unitCodes = $product->productUnits()->pluck('unit_code')->toArray();
                foreach ($unitCodes as $unitCode) {
                    Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
                }
                Cache::forget($this->getProductCacheKey($product->id));
                $newProducts = array_diff(Cache::get($this->getUserCacheKey('new_products'), []), [$product->id]);
                $updatedProducts = array_diff(Cache::get($this->getUserCacheKey('updated_products'), []), [$product->id]);
                $existingMismatches = Cache::get($this->getUserCacheKey('stock_mismatches'), []);
                unset($existingMismatches[$product->id]);
                $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
                unset($brandNames[$product->id]);
                Cache::forever($this->getUserCacheKey('new_products'), $newProducts);
                Cache::forever($this->getUserCacheKey('updated_products'), $updatedProducts);
                Cache::forever($this->getUserCacheKey('stock_mismatches'), $existingMismatches);
                Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

                ProductHistory::create([
                    'type' => 'keluar',
                    'product_id' => $product->id,
                    'brand' => $brandNames[$product->id] ?? explode(' ', trim($product->name))[0],
                    'model' => trim(str_replace($brandNames[$product->id] ?? explode(' ', trim($product->name))[0], '', $product->name)),
                    'size' => $product->size,
                    'color' => $product->color,
                    'stock' => 0,
                    'stock_change' => '0 unit',
                    'selling_price' => $product->selling_price,
                    'discount_price' => $product->discount_price ?? null,
                    'user_id' => Auth::id() ?? 'guest',
                    'user_name' => Auth::user()->name ?? 'Unknown',
                    'timestamp' => Carbon::now('Asia/Jakarta'),
                ]);

                $product->delete();
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
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
            $userId = Auth::id() ?? 'guest';
            $userName = Auth::user()->name ?? 'Unknown';

            $currentStock = $product->productUnits()->where('is_active', true)->count();
            $desiredStock = $validated['sizes'][0]['stock'];
            $stockChange = $desiredStock - $currentStock;
            $stockChangeDescription = $stockChange > 0 ? "+{$stockChange} unit" : ($stockChange < 0 ? "-" . abs($stockChange) . " unit" : "tidak berubah");

            ProductHistory::create([
                'type' => 'perbarui',
                'product_id' => $product->id,
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'size' => $validated['sizes'][0]['size'],
                'color' => $validated['color'],
                'stock' => $desiredStock,
                'stock_change' => $stockChangeDescription,
                'selling_price' => $validated['selling_price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'user_id' => $userId,
                'user_name' => $userName,
                'timestamp' => Carbon::now('Asia/Jakarta'),
            ]);

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
                    $qrCode = route('inventory.show_unit', ['product' => $product->id, 'unitCode' => $unit->unit_code]);
                    $unit->update(['qr_code' => $qrCode]);
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $qrCode,
                        'is_active' => true,
                    ]);
                    $newUnitCodes[] = $unit->unit_code;
                }
            } elseif ($desiredStock < $currentStock) {
                $unitsToDeactivate = $product->productUnits()->where('is_active', true)->take($currentStock - $desiredStock)->get();
                foreach ($unitsToDeactivate as $unit) {
                    $unit->update(['is_active' => false]);
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $unit->qr_code,
                        'is_active' => false,
                    ]);
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

                ProductHistory::create([
                    'type' => 'masuk',
                    'product_id' => $newProduct->id,
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'size' => $sizeData['size'],
                    'color' => $validated['color'],
                    'stock' => $sizeData['stock'],
                    'stock_change' => "+{$sizeData['stock']} unit",
                    'selling_price' => $validated['selling_price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'timestamp' => Carbon::now('Asia/Jakarta'),
                ]);

                $units = [];
                $productNewUnitCodes = [];
                for ($j = 0; $j < $sizeData['stock']; $j++) {
                    $unitCode = 'UNIT-' . strtoupper(Str::random(8));
                    $unit = ProductUnit::create([
                        'product_id' => $newProduct->id,
                        'unit_code' => $unitCode,
                        'qr_code' => '',
                        'is_active' => true,
                    ]);
                    $qrCode = route('inventory.show_unit', ['product' => $newProduct->id, 'unitCode' => $unit->unit_code]);
                    $unit->update(['qr_code' => $qrCode]);
                    $units[] = $unit;
                    $productNewUnitCodes[] = $unit->unit_code;
                }

                Cache::forever($this->getProductCacheKey($newProduct->id), [
                    'id' => $newProduct->id,
                    'name' => $newProduct->name,
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'size' => $newProduct->size,
                    'color' => $newProduct->color,
                    'selling_price' => $newProduct->selling_price,
                    'discount_price' => $newProduct->discount_price,
                    'stock' => $sizeData['stock'],
                ]);

                foreach ($units as $unit) {
                    Cache::forever($this->getUnitCacheKey($newProduct->id, $unit->unit_code), [
                        'product_id' => $newProduct->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $unit->qr_code,
                        'is_active' => true,
                    ]);
                }

                Cache::forever($this->getUserCacheKey('new_units_' . $newProduct->id), $productNewUnitCodes);
                Log::info("Stored new units for new product ID {$newProduct->id}: " . json_encode($productNewUnitCodes));
            }

            Cache::forever($this->getUserCacheKey('new_units_' . $product->id), $newUnitCodes);
            Cache::forever($this->getUserCacheKey('new_products'), array_merge(Cache::get($this->getUserCacheKey('new_products'), []), $newProductIds));
            Cache::forever($this->getUserCacheKey('updated_products'), array_merge(Cache::get($this->getUserCacheKey('updated_products'), []), $updatedProductIds));
            Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

            Cache::forever($this->getProductCacheKey($product->id), [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'size' => $product->size,
                'color' => $product->color,
                'selling_price' => $product->selling_price,
                'discount_price' => $product->discount_price,
                'stock' => $desiredStock,
            ]);

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
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
            $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
            $model = trim(str_replace($brand, '', $product->name));
            $stock = $product->productUnits()->where('is_active', true)->count();
            $userId = Auth::id() ?? 'guest';
            $userName = Auth::user()->name ?? 'Unknown';

            ProductHistory::create([
                'type' => 'keluar',
                'product_id' => $product->id,
                'brand' => $brand,
                'model' => $model,
                'size' => $product->size,
                'color' => $product->color,
                'stock' => $stock,
                'stock_change' => "-{$stock} unit",
                'selling_price' => $product->selling_price,
                'discount_price' => $product->discount_price ?? null,
                'user_id' => $userId,
                'user_name' => $userName,
                'timestamp' => Carbon::now('Asia/Jakarta'),
            ]);

            $product->productUnits()->delete();
            $unitCodes = $product->productUnits()->pluck('unit_code')->toArray();
            foreach ($unitCodes as $unitCode) {
                Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
            }
            Cache::forget($this->getProductCacheKey($product->id));
            Cache::forget($this->getUserCacheKey('new_units_' . $product->id));
            $newProducts = array_diff(Cache::get($this->getUserCacheKey('new_products'), []), [$id]);
            $updatedProducts = array_diff(Cache::get($this->getUserCacheKey('updated_products'), []), [$id]);
            $existingMismatches = Cache::get($this->getUserCacheKey('stock_mismatches'), []);
            unset($existingMismatches[$id]);
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
            unset($brandNames[$id]);
            Cache::forever($this->getUserCacheKey('new_products'), $newProducts);
            Cache::forever($this->getUserCacheKey('updated_products'), $updatedProducts);
            Cache::forever($this->getUserCacheKey('stock_mismatches'), $existingMismatches);
            Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);
            $product->delete();
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
                $product = (object) $cachedProduct;
                $product->productUnits = collect([]);
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
        $reports = StockOpnameReport::with(['product', 'user'])
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'product_id' => $report->product_id,
                    'name' => $report->name,
                    'brand' => $report->brand,
                    'model' => $report->model,
                    'size' => $report->size,
                    'color' => $report->color,
                    'system_stock' => $report->system_stock,
                    'physical_stock' => $report->physical_stock,
                    'difference' => $report->difference,
                    'scanned_qr_codes' => $report->scanned_qr_codes,
                    'unscanned_qr_codes' => $report->unscanned_qr_codes,
                    'timestamp' => $report->timestamp->toDateTimeString(),
                ];
            })->toArray();

        $previousPhysicalStocks = StockOpnameReport::pluck('physical_stock', 'product_id')->toArray();

        Cache::forget($this->getUserCacheKey('stock_mismatches'));
        Cache::forget($this->getUserCacheKey('new_products'));
        Cache::forget($this->getUserCacheKey('updated_products'));
        Cache::forget($this->getUserCacheKey('brand_names'));
        foreach ($reports as $report) {
            Cache::forget($this->getUserCacheKey('new_units_' . $report['product_id']));
        }

        return view('inventory.stock_opname', compact('totalStock', 'reports', 'previousPhysicalStocks'));
    }

    public function validateQrCode(Request $request)
    {
        try {
            $request->validate([
                'qr_code' => 'required|string',
            ]);

            $qrCode = $request->input('qr_code');

            // Periksa apakah QR code sudah ada di laporan sebelumnya
            $exists = StockOpnameReport::whereJsonContains('scanned_qr_codes', $qrCode)->exists();

            if ($exists) {
                return response()->json([
                    'valid' => false,
                    'message' => 'QR code sudah ada di laporan sebelumnya dan tidak dapat dipindai ulang.'
                ], 422);
            }

            // Validasi format QR code
            if (!str_contains($qrCode, 'inventory')) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Format QR code tidak valid. Harus mengandung "/inventory".'
                ], 422);
            }

            $urlParts = explode('/', $qrCode);
            $productIndex = array_search('inventory', $urlParts);
            if ($productIndex === false || !isset($urlParts[$productIndex + 1])) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Format QR code tidak valid. Tidak dapat menemukan ID produk.'
                ], 422);
            }

            $productId = $urlParts[$productIndex + 1];
            if (!ctype_digit($productId)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'ID produk tidak valid. Harus berupa angka.'
                ], 422);
            }

            $product = Product::find($productId);

            if (!$product) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Produk dengan ID ' . $productId . ' tidak ditemukan di database.'
                ], 404);
            }

            // Validasi apakah unit_code dari QR code ada di ProductUnit
            $unitCode = basename(parse_url($qrCode, PHP_URL_PATH));
            $unitExists = ProductUnit::where('product_id', $productId)
                ->where('unit_code', $unitCode)
                ->where('is_active', true)
                ->exists();

            if (!$unitExists) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Unit dengan kode ' . $unitCode . ' tidak ditemukan atau tidak aktif.'
                ], 404);
            }

            \Log::info('Validated QR code: ' . $qrCode . ', Product ID: ' . $productId . ', Unit Code: ' . $unitCode);

            return response()->json([
                'valid' => true,
                'product_id' => $productId,
                'unit_code' => $unitCode
            ]);
        } catch (\Exception $e) {
            \Log::error('Error validating QR code: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'message' => 'Gagal memvalidasi QR code: ' . $e->getMessage()
            ], 500);
        }
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
            $stockMessage = '';
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
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
                $stockMessage = 'Produk dihapus karena stok fisik 0.';
                $stockMismatch['message'] = 'Produk dihapus karena stok fisik 0.';
                $unitsToDeactivate = $product->productUnits()->where('is_active', true)->get();
                foreach ($unitsToDeactivate as $unit) {
                    $unit->update(['is_active' => false]);
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $unit->qr_code,
                        'is_active' => false,
                    ]);
                    $newUnitCodes = array_diff($newUnitCodes, [$unit->unit_code]);
                }
                $unitCodes = $product->productUnits()->pluck('unit_code')->toArray();
                foreach ($unitCodes as $unitCode) {
                    Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
                }
                Cache::forget($this->getProductCacheKey($product->id));
                $newProducts = array_diff(Cache::get($this->getUserCacheKey('new_products'), []), [$product->id]);
                $updatedProducts = array_diff(Cache::get($this->getUserCacheKey('updated_products'), []), [$product->id]);
                $existingMismatches = Cache::get($this->getUserCacheKey('stock_mismatches'), []);
                unset($existingMismatches[$product->id]);
                $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
                unset($brandNames[$product->id]);
                Cache::forever($this->getUserCacheKey('new_products'), $newProducts);
                Cache::forever($this->getUserCacheKey('updated_products'), $updatedProducts);
                Cache::forever($this->getUserCacheKey('new_units_' . $product->id), null);
                Cache::forever($this->getUserCacheKey('stock_mismatches'), $existingMismatches);
                Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

                ProductHistory::create([
                    'type' => 'keluar',
                    'product_id' => $product->id,
                    'brand' => $brand,
                    'model' => $model,
                    'size' => $product->size,
                    'color' => $product->color,
                    'stock' => 0,
                    'stock_change' => "-{$currentStock} unit",
                    'selling_price' => $product->selling_price,
                    'discount_price' => $product->discount_price ?? null,
                    'user_id' => Auth::id() ?? 'guest',
                    'user_name' => Auth::user()->name ?? 'Unknown',
                    'timestamp' => Carbon::now('Asia/Jakarta'),
                ]);

                $product->productUnits()->delete();
                $product->delete();
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
                    $qrCode = route('inventory.show_unit', ['product' => $product->id, 'unitCode' => $unit->unit_code]);
                    $unit->update(['qr_code' => $qrCode]);
                    $newUnitCodes[] = $unit->unit_code;
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $qrCode,
                        'is_active' => true,
                    ]);

                    ProductHistory::create([
                        'type' => 'masuk',
                        'product_id' => $product->id,
                        'brand' => $brand,
                        'model' => $model,
                        'size' => $product->size,
                        'color' => $product->color,
                        'stock' => $physicalStock,
                        'stock_change' => '+1 unit',
                        'selling_price' => $product->selling_price,
                        'discount_price' => $product->discount_price ?? null,
                        'user_id' => Auth::id() ?? 'guest',
                        'user_name' => Auth::user()->name ?? 'Unknown',
                        'timestamp' => Carbon::now('Asia/Jakarta'),
                    ]);
                }
            } elseif ($difference < 0) {
                $stockMessage = "Stok fisik: {$physicalStock}, Stok sistem: {$currentStock}, Selisih: -" . abs($difference) . " unit";
                $stockMismatch['message'] = "Stok tidak sesuai, kurang " . abs($difference) . " unit";
                $unitsToDeactivate = $product->productUnits()->where('is_active', true)->take(abs($difference))->get();
                foreach ($unitsToDeactivate as $unit) {
                    $unit->update(['is_active' => false]);
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $unit->qr_code,
                        'is_active' => false,
                    ]);
                    $newUnitCodes = array_diff($newUnitCodes, [$unit->unit_code]);

                    ProductHistory::create([
                        'type' => 'keluar',
                        'product_id' => $product->id,
                        'brand' => $brand,
                        'model' => $model,
                        'size' => $product->size,
                        'color' => $product->color,
                        'stock' => $physicalStock,
                        'stock_change' => '-1 unit',
                        'selling_price' => $product->selling_price,
                        'discount_price' => $product->discount_price ?? null,
                        'user_id' => Auth::id() ?? 'guest',
                        'user_name' => Auth::user()->name ?? 'Unknown',
                        'timestamp' => Carbon::now('Asia/Jakarta'),
                    ]);
                }
            } else {
                $stockMessage = "Stok fisik sesuai dengan stok sistem: {$physicalStock} unit";
                $stockMismatch['message'] = "Stok sesuai dengan sistem";
            }

            $existingMismatches = Cache::get($this->getUserCacheKey('stock_mismatches'), []);
            $existingMismatches[$product->id] = $stockMismatch;
            Cache::forever($this->getUserCacheKey('stock_mismatches'), $existingMismatches);
            Cache::forever($this->getUserCacheKey('new_units_' . $product->id), $newUnitCodes);

            Cache::forever($this->getProductCacheKey($product->id), [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $brandNames[$product->id] ?? explode(' ', trim($product->name))[0],
                'model' => $model,
                'size' => $product->size,
                'color' => $product->color,
                'selling_price' => $product->selling_price,
                'discount_price' => $product->discount_price,
                'stock' => $physicalStock,
            ]);

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
            DB::beginTransaction();

            $skippedQRCodes = [];

            foreach ($validated['reports'] as $reportData) {
                $productId = $reportData['product_id'];
                $scannedQRCodes = $reportData['scanned_qr_codes'] ?? [];

                // Cari laporan terakhir untuk product_id ini
                $latestReport = StockOpnameReport::where('product_id', $productId)
                    ->orderBy('timestamp', 'desc')
                    ->first();

                $brand = $reportData['name'] ? explode(' ', trim($reportData['name']))[0] : 'Unknown';
                $model = $reportData['name'] ? trim(str_replace($brand, '', $reportData['name'])) : 'Unknown';

                $product = Product::find($productId);

                // Filter QR code yang sudah ada di semua laporan untuk produk ini
                $existingQRCodes = StockOpnameReport::where('product_id', $productId)
                    ->whereNotNull('scanned_qr_codes')
                    ->pluck('scanned_qr_codes')
                    ->flatten()
                    ->map(function ($qr) {
                        return basename(parse_url($qr, PHP_URL_PATH));
                    })
                    ->toArray();

                $filteredScannedQRCodes = [];
                foreach ($scannedQRCodes as $qrCode) {
                    $unitCode = basename(parse_url($qrCode, PHP_URL_PATH));
                    if (in_array($unitCode, $existingQRCodes)) {
                        $skippedQRCodes[] = $unitCode;
                        continue;
                    }
                    $filteredScannedQRCodes[] = $qrCode;
                }

                // Jika semua QR code sudah ada, lewati
                if (empty($filteredScannedQRCodes) && !empty($scannedQRCodes)) {
                    Log::warning("All QR codes for product ID {$productId} already exist, skipping.");
                    continue;
                }

                $allUnitCodes = $product ? ProductUnit::where('product_id', $productId)
                    ->where('is_active', true)
                    ->pluck('unit_code')
                    ->toArray() : [];
                $unscannedQRCodes = array_diff($allUnitCodes, array_map(function ($qr) {
                    return basename(parse_url($qr, PHP_URL_PATH));
                }, $filteredScannedQRCodes));

                if ($reportData['physical_stock'] == 0 && $product) {
                    // Logika hapus produk jika stok fisik 0
                    $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
                    $stockMismatch = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'brand' => $brandNames[$product->id] ?? explode(' ', trim($product->name))[0],
                        'model' => trim(str_replace($brandNames[$product->id] ?? explode(' ', trim($product->name))[0], '', $product->name)),
                        'physical_stock' => 0,
                        'system_stock' => $reportData['system_stock'],
                        'difference' => -$reportData['system_stock'],
                        'message' => 'Produk dihapus karena stok fisik 0.'
                    ];

                    $product->productUnits()->delete();
                    $unitCodes = $product->productUnits()->pluck('unit_code')->toArray();
                    foreach ($unitCodes as $unitCode) {
                        Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
                    }
                    Cache::forget($this->getProductCacheKey($product->id));
                    $newProducts = array_diff(Cache::get($this->getUserCacheKey('new_products'), []), [$product->id]);
                    $updatedProducts = array_diff(Cache::get($this->getUserCacheKey('updated_products'), []), [$product->id]);
                    $existingMismatches = Cache::get($this->getUserCacheKey('stock_mismatches'), []);
                    $existingMismatches[$product->id] = $stockMismatch;
                    Cache::forever($this->getUserCacheKey('stock_mismatches'), $existingMismatches);
                    $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
                    unset($brandNames[$product->id]);
                    Cache::forever($this->getUserCacheKey('new_products'), $newProducts);
                    Cache::forever($this->getUserCacheKey('updated_products'), $updatedProducts);
                    Cache::forever($this->getUserCacheKey('new_units_' . $product->id), null);
                    Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

                    ProductHistory::create([
                        'type' => 'keluar',
                        'product_id' => $product->id,
                        'brand' => $brandNames[$product->id] ?? explode(' ', trim($product->name))[0],
                        'model' => trim(str_replace($brandNames[$product->id] ?? explode(' ', trim($product->name))[0], '', $product->name)),
                        'size' => $product->size,
                        'color' => $product->color,
                        'stock' => 0,
                        'stock_change' => "-{$reportData['system_stock']} unit",
                        'selling_price' => $product->selling_price,
                        'discount_price' => $product->discount_price ?? null,
                        'user_id' => Auth::id() ?? 'guest',
                        'user_name' => Auth::user()->name ?? 'Unknown',
                        'timestamp' => Carbon::now('Asia/Jakarta'),
                    ]);

                    $product->delete();
                    if ($latestReport) {
                        $latestReport->delete();
                    }
                    continue;
                }

                $reportAttributes = [
                    'name' => $reportData['name'] ?? 'Produk Tidak Diketahui',
                    'brand' => $brand,
                    'model' => $model,
                    'size' => $reportData['size'] ?? '-',
                    'color' => $reportData['color'] ?? '-',
                    'system_stock' => $reportData['system_stock'],
                    'physical_stock' => $reportData['physical_stock'],
                    'difference' => $reportData['difference'],
                    'unscanned_qr_codes' => array_values($unscannedQRCodes),
                    'user_id' => Auth::id() ?? null,
                    'user_name' => Auth::user()->name ?? 'Unknown',
                    'timestamp' => Carbon::now('Asia/Jakarta'),
                ];

                if ($latestReport) {
                    // Update laporan terakhir
                    $existingScannedQRCodes = $latestReport->scanned_qr_codes ?? [];
                    $reportAttributes['scanned_qr_codes'] = array_unique(array_merge($existingScannedQRCodes, $filteredScannedQRCodes));
                    $latestReport->update($reportAttributes);
                } else {
                    // Buat laporan baru jika belum ada
                    $reportAttributes['product_id'] = $productId;
                    $reportAttributes['scanned_qr_codes'] = $filteredScannedQRCodes;
                    StockOpnameReport::create($reportAttributes);
                }
            }

            DB::commit();

            $response = [
                'success' => true,
                'message' => 'Laporan stock opname berhasil disimpan atau diperbarui',
            ];

            if (!empty($skippedQRCodes)) {
                $response['message'] .= '. Beberapa QR code tidak disimpan karena sudah ada: ' . implode(', ', $skippedQRCodes);
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollBack();
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
        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);

        return view('inventory.product-status', compact('newProducts', 'brandNames'));
    }

    public function deleteReport($id)
    {
        try {
            $report = StockOpnameReport::findOrFail($id);
            $report->delete();
            return redirect()->route('inventory.stock_opname')->with('success', 'Laporan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Failed to delete stock opname report: ' . $e->getMessage());
            return redirect()->route('inventory.stock_opname')->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    public function deleteAllReports()
    {
        try {
            StockOpnameReport::truncate();
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