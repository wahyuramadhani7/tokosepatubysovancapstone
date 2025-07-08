<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB; // Impor DB facade
use Illuminate\Support\Str;
use Carbon\Carbon;

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

    public function index()
    {
        $products = Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })
        ->orderBy('name')
        ->orderBy('size')
        ->paginate(10);

        $totalProducts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })->count();

        $totalStock = ProductUnit::where('is_active', true)->count();

        $lowStockProducts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        }, '<=', 10)->count();

        $brandCounts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })
        ->withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->get()
        ->groupBy(function ($product) {
            return Str::lower(explode(' ', trim($product->name))[0]);
        })
        ->map(function ($group) {
            $brandName = Str::title($group->first()->name ? explode(' ', trim($group->first()->name))[0] : 'Unknown');
            return [
                'name' => $brandName,
                'count' => $group->sum('product_units_count')
            ];
        })
        ->sortBy('name')
        ->pluck('count', 'name');

        $newProducts = session('new_products', []);
        $updatedProducts = session('updated_products', []);

        return view('inventory.index', compact('products', 'totalProducts', 'lowStockProducts', 'totalStock', 'brandCounts', 'newProducts', 'updatedProducts'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $cacheKey = 'product_search_' . md5($searchTerm . '_' . $request->input('page', 1));

        $products = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($searchTerm) {
            return Product::withCount(['productUnits' => function ($query) {
                $query->where('is_active', true);
            }])
            ->whereHas('productUnits', function ($query) {
                $query->where('is_active', true);
            })
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('color', 'like', '%' . $searchTerm . '%')
                      ->orWhere('size', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy('name')
            ->orderBy('size')
            ->paginate(10)
            ->appends(['search' => $searchTerm]);
        });

        $totalProducts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })->count();
        $totalStock = ProductUnit::where('is_active', true)->count();
        $lowStockProducts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        }, '<=', 10)->count();

        $brandCounts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })
        ->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('color', 'like', '%' . $searchTerm . '%')
                  ->orWhere('size', 'like', '%' . $searchTerm . '%');
        })
        ->withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->get()
        ->groupBy(function ($product) {
            return Str::lower(explode(' ', trim($product->name))[0]);
        })
        ->map(function ($group) {
            $brandName = Str::title($group->first()->name ? explode(' ', trim($group->first()->name))[0] : 'Unknown');
            return [
                'name' => $brandName,
                'count' => $group->sum('product_units_count')
            ];
        })
        ->sortBy('name')
        ->pluck('count', 'name');

        $newProducts = session('new_products', []);
        $updatedProducts = session('updated_products', []);

        return view('inventory.index', compact('products', 'totalProducts', 'lowStockProducts', 'totalStock', 'searchTerm', 'brandCounts', 'newProducts', 'updatedProducts'));
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

                $units = [];
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
                }

                Cache::forever($this->getProductCacheKey($product->id), [
                    'id' => $product->id,
                    'name' => $product->name,
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
            }

            session(['new_units_all' => $newUnitCodes, 'new_products' => $newProductIds]);

            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Produk dan unit berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
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
            $similarProducts = Product::withCount(['productUnits' => function ($query) {
                $query->where('is_active', true);
            }])
            ->where('name', $product->name)
            ->where('id', '!=', $productId)
            ->whereHas('productUnits', function ($query) {
                $query->where('is_active', true);
            })
            ->get()
            ->map(function ($similarProduct) {
                return [
                    'id' => $similarProduct->id,
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

        return response()->json([
            'id' => $product->id,
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
                session()->forget('new_units_' . $product->id);
                $newProducts = array_diff(session('new_products', []), [$product->id]);
                $updatedProducts = array_diff(session('updated_products', []), [$product->id]);
                $existingMismatches = session('stock_mismatches', []);
                unset($existingMismatches[$product->id]);
                session([
                    'new_products' => $newProducts,
                    'updated_products' => $updatedProducts,
                    'stock_mismatches' => $existingMismatches,
                ]);
                $product->delete();
                DB::commit();
                return redirect()->route('inventory.index')->with('success', 'Produk dihapus karena stok 0.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('inventory.index')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
            }
        }

        DB::beginTransaction();

        try {
            $product->update([
                'name' => trim($validated['brand'] . ' ' . $validated['model']),
                'size' => $validated['sizes'][0]['size'],
                'color' => $validated['color'],
                'selling_price' => $validated['selling_price'],
                'discount_price' => $validated['discount_price'] ?? null,
            ]);

            $currentStock = $product->productUnits()->where('is_active', true)->count();
            $desiredStock = $validated['sizes'][0]['stock'];
            $newUnitCodes = session('new_units_' . $product->id, []);
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

                $units = [];
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
                    $newUnitCodes[] = $unit->unit_code;
                }

                Cache::forever($this->getProductCacheKey($newProduct->id), [
                    'id' => $newProduct->id,
                    'name' => $newProduct->name,
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
            }

            session([
                'new_units_' . $product->id => $newUnitCodes,
                'new_products' => array_merge(session('new_products', []), $newProductIds),
                'updated_products' => array_merge(session('updated_products', []), $updatedProductIds),
            ]);

            Cache::forever($this->getProductCacheKey($product->id), [
                'id' => $product->id,
                'name' => $product->name,
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
            $product->productUnits()->delete();
            $unitCodes = $product->productUnits()->pluck('unit_code')->toArray();
            foreach ($unitCodes as $unitCode) {
                Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
            }
            Cache::forget($this->getProductCacheKey($product->id));
            $newProducts = array_diff(session('new_products', []), [$id]);
            $updatedProducts = array_diff(session('updated_products', []), [$id]);
            $existingMismatches = session('stock_mismatches', []);
            unset($existingMismatches[$id]);
            session([
                'new_products' => $newProducts,
                'updated_products' => $updatedProducts,
                'new_units_' . $id => null,
                'stock_mismatches' => $existingMismatches,
            ]);
            $product->delete();
            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Produk dan unit berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
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
                return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
            }
        }

        $newUnitCodes = session('new_units_' . $id, []);

        if (!empty($newUnitCodes)) {
            $product->load(['productUnits' => function ($query) use ($newUnitCodes) {
                $query->where('is_active', true)->whereIn('unit_code', $newUnitCodes);
            }]);
        }

        session()->forget('new_units_' . $id);

        return view('inventory.print_qr', compact('product'));
    }

    public function stockOpname()
    {
        $totalStock = ProductUnit::where('is_active', true)->count();
        $reports = Cache::get('stock_opname_reports', []);

        return view('inventory.stock_opname', compact('totalStock', 'reports'));
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
            $stockMismatch = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'physical_stock' => $physicalStock,
                'system_stock' => $currentStock,
                'difference' => $difference,
            ];
            $newUnitCodes = session('new_units_' . $product->id, []);

            if ($physicalStock == 0) {
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
                $newProducts = array_diff(session('new_products', []), [$product->id]);
                $updatedProducts = array_diff(session('updated_products', []), [$product->id]);
                $existingMismatches = session('stock_mismatches', []);
                unset($existingMismatches[$product->id]);
                session([
                    'new_products' => $newProducts,
                    'updated_products' => $updatedProducts,
                    'new_units_' . $product->id => null,
                    'stock_mismatches' => $existingMismatches,
                ]);
                $product->productUnits()->delete();
                $product->delete();
                $stockMessage = 'Produk dihapus karena stok fisik 0.';
                $stockMismatch['message'] = 'Produk dihapus karena stok fisik 0.';
            } elseif ($difference > 0) {
                $stockMessage = "Stok fisik: {$physicalStock}, Stok sistem: {$currentStock}, Selisih: +{$difference} unit";
                $stockMismatch['message'] = "Stok tidak sesuai, lebih {$difference} unit";
                for ($i = $currentStock; $i < $physicalStock; $i++) {
                    $unitCode = 'UNIT-' . strtoupper(Str::random(8));
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
                }
            } else {
                $stockMessage = "Stok fisik sesuai dengan stok sistem: {$physicalStock} unit";
                $stockMismatch['message'] = "Stok sesuai dengan sistem";
            }

            $existingMismatches = session('stock_mismatches', []);
            $existingMismatches[$product->id] = $stockMismatch;
            session(['stock_mismatches' => $existingMismatches, 'new_units_' . $product->id => $newUnitCodes]);

            Cache::forever($this->getProductCacheKey($product->id), [
                'id' => $product->id,
                'name' => $product->name,
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
                    'physical_stock' => $physicalStock,
                    'system_stock' => $currentStock,
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
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
            'reports.*.name' => 'required|string',
            'reports.*.size' => 'required|string',
            'reports.*.color' => 'required|string',
            'reports.*.system_stock' => 'required|integer',
            'reports.*.physical_stock' => 'required|integer',
            'reports.*.difference' => 'required|integer',
        ]);

        try {
            $reports = Cache::get('stock_opname_reports', []);
            foreach ($validated['reports'] as $report) {
                if ($report['physical_stock'] == 0) {
                    $product = Product::find($report['product_id']);
                    if ($product) {
                        DB::beginTransaction();
                        try {
                            $product->productUnits()->delete();
                            $unitCodes = $product->productUnits()->pluck('unit_code')->toArray();
                            foreach ($unitCodes as $unitCode) {
                                Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
                            }
                            Cache::forget($this->getProductCacheKey($product->id));
                            $newProducts = array_diff(session('new_products', []), [$product->id]);
                            $updatedProducts = array_diff(session('updated_products', []), [$product->id]);
                            $existingMismatches = session('stock_mismatches', []);
                            unset($existingMismatches[$product->id]);
                            session([
                                'new_products' => $newProducts,
                                'updated_products' => $updatedProducts,
                                'new_units_' . $product->id => null,
                                'stock_mismatches' => $existingMismatches,
                            ]);
                            $product->delete();
                            DB::commit();
                            continue;
                        } catch (\Exception $e) {
                            DB::rollBack();
                            continue;
                        }
                    }
                }
                $reports[] = [
                    'product_id' => $report['product_id'],
                    'name' => $report['name'],
                    'size' => $report['size'],
                    'color' => $report['color'],
                    'system_stock' => $report['system_stock'],
                    'physical_stock' => $report['physical_stock'],
                    'difference' => $report['difference'],
                    'timestamp' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                ];
            }
            Cache::put('stock_opname_reports', $reports, now()->addHours(24));

            return response()->json([
                'success' => true,
                'message' => 'Laporan stock opname berhasil disimpan',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan laporan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteReport($index)
    {
        try {
            $reports = Cache::get('stock_opname_reports', []);
            if (isset($reports[$index])) {
                unset($reports[$index]);
                $reports = array_values($reports);
                Cache::put('stock_opname_reports', $reports, now()->addHours(24));
                return redirect()->route('inventory.stock_opname')->with('success', 'Laporan berhasil dihapus');
            }
            return redirect()->route('inventory.stock_opname')->with('error', 'Laporan tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('inventory.stock_opname')->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }
}