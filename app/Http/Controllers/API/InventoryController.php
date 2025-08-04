<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    private function getSearchCacheKey($searchTerm, $sizeTerm, $page, $perPage, $orderBy, $sort)
    {
        return 'product_search_' . md5($searchTerm . '_' . $sizeTerm . '_' . $page . '_' . $perPage . '_' . $orderBy . '_' . $sort);
    }

    /**
     * Clear all search-related caches
     */
    private function clearSearchCaches()
    {
        $keys = Cache::getRedis()->keys('product_search_*');
        foreach ($keys as $key) {
            Cache::forget(str_replace(Cache::getPrefix(), '', $key));
        }
    }

    /**
     * Get all products with pagination and optional search
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $searchTerm = $request->query('search', '');
        $sizeTerm = $request->query('size', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 100);
        $orderBy = $request->query('order_by', 'created_at');
        $sort = $request->query('sort', 'desc');
        $noCache = $request->query('no_cache', false);
        $cacheKey = $this->getSearchCacheKey($searchTerm, $sizeTerm, $page, $perPage, $orderBy, $sort);

        $products = $noCache ? null : Cache::get($cacheKey);

        if (!$products) {
            $query = Product::withCount(['productUnits' => function ($query) {
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
            });

            // Apply dynamic sorting
            $query->orderBy($orderBy, $sort);

            $products = $query->paginate($perPage)
                ->appends([
                    'search' => $searchTerm,
                    'size' => $sizeTerm,
                    'per_page' => $perPage,
                    'order_by' => $orderBy,
                    'sort' => $sort,
                ]);

            if (!$noCache) {
                Cache::put($cacheKey, $products, now()->addMinutes(1));
            }
        }

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
        ->groupBy(function ($product) {
            return Str::lower(explode(' ', trim($product->name))[0]);
        })
        ->map(function ($group) {
            $brandName = Str::title(explode(' ', trim($group->first()->name))[0] ?? 'Unknown');
            return [
                'name' => $brandName,
                'count' => $group->sum('product_units_count'),
            ];
        })
        ->sortBy('name')
        ->pluck('count', 'name');

        return response()->json([
            'success' => true,
            'data' => [
                'products' => collect($products->items())->map(function ($product) {
                    $brand = explode(' ', trim($product->name))[0];
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
                }),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                    'per_page' => $products->perPage(),
                ],
                'total_products' => $totalProducts,
                'total_stock' => $totalStock,
                'low_stock_products' => $lowStockProducts,
                'brand_counts' => $brandCounts,
            ]
        ], 200, ['Cache-Control' => 'no-cache']);
    }

    /**
     * Create a new product
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak disimpan karena stok 0.'
            ], 400);
        }

        DB::beginTransaction();

        try {
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

                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $unit->qr_code,
                        'is_active' => true,
                    ]);
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
            }

            // Clear search caches to ensure new products appear
            $this->clearSearchCaches();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Produk dan unit berhasil ditambahkan',
                'products' => $newProductIds,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single product
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
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
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }
        }

        $brand = explode(' ', trim($product->name))[0];
        $model = trim(str_replace($brand, '', $product->name));

        return response()->json([
            'success' => true,
            'data' => [
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
            ]
        ], 200, ['Cache-Control' => 'no-cache']);
    }

    /**
     * Get a specific product unit
     *
     * @param int $productId
     * @param string $unitCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUnit($productId, $unitCode)
    {
        $product = Product::with(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->find($productId);
        $unit = ProductUnit::where('product_id', $productId)->where('unit_code', $unitCode)->first();

        if (!$product || !$unit) {
            $cachedProduct = Cache::get($this->getProductCacheKey($productId));
            $cachedUnit = Cache::get($this->getUnitCacheKey($productId, $unitCode));

            if ($cachedProduct && $cachedUnit) {
                $product = (object) $cachedProduct;
                $unit = (object) $cachedUnit;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk atau unit tidak ditemukan',
                    'data' => [
                        'product' => [
                            'id' => $productId,
                            'name' => 'Produk Tidak Ditemukan',
                            'size' => 'N/A',
                            'color' => 'N/A',
                            'selling_price' => 0,
                            'discount_price' => null,
                            'stock' => 0,
                        ],
                        'unit' => [
                            'unit_code' => $unitCode,
                            'qr_code' => route('inventory.show_unit', ['product' => $productId, 'unitCode' => $unitCode]),
                            'is_active' => false,
                        ],
                        'similar_products' => [],
                    ]
                ], 404);
            }
        }

        $brand = explode(' ', trim($product->name))[0];
        $model = trim(str_replace($brand, '', $product->name));

        $similarProducts = Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->where('name', 'like', $brand . '%')
        ->where('id', '!=', $productId)
        ->whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })
        ->get()
        ->map(function ($similarProduct) {
            $brand = explode(' ', trim($similarProduct->name))[0];
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

        return response()->json([
            'success' => true,
            'data' => [
                'product' => [
                    'id' => $product->id,
                    'brand' => $brand,
                    'model' => $model,
                    'name' => $product->name,
                    'size' => $product->size,
                    'color' => $product->color,
                    'selling_price' => $product->selling_price,
                    'discount_price' => $product->discount_price,
                    'stock' => $product->productUnits->count(),
                ],
                'unit' => [
                    'unit_code' => $unit->unit_code,
                    'qr_code' => $unit->qr_code,
                    'is_active' => $unit->is_active,
                ],
                'similar_products' => $similarProducts,
            ]
        ], 200, ['Cache-Control' => 'no-cache']);
    }

    /**
     * Update a product
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
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
                foreach ($product->productUnits()->pluck('unit_code')->toArray() as $unitCode) {
                    Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
                }
                Cache::forget($this->getProductCacheKey($product->id));
                $product->delete();
                $this->clearSearchCaches();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Produk dihapus karena stok 0.',
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus produk: ' . $e->getMessage(),
                ], 500);
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
                    Cache::forever($this->getUnitCacheKey($newProduct->id, $unit->unit_code), [
                        'product_id' => $newProduct->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $unit->qr_code,
                        'is_active' => true,
                    ]);
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
                'stock' => $desiredStock,
            ]);

            $this->clearSearchCaches();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Produk dan unit berhasil diperbarui',
                'product_id' => $product->id,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a product
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        DB::beginTransaction();

        try {
            $product->productUnits()->delete();
            foreach ($product->productUnits()->pluck('unit_code')->toArray() as $unitCode) {
                Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
            }
            Cache::forget($this->getProductCacheKey($product->id));
            $product->delete();
            $this->clearSearchCaches();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Produk dan unit berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get stock opname data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stockOpname()
    {
        $totalStock = ProductUnit::where('is_active', true)->count();
        $reports = Cache::get('stock_opname_reports', []);

        $previousPhysicalStocks = [];
        foreach ($reports as $report) {
            $previousPhysicalStocks[$report['product_id']] = $report['physical_stock'];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_stock' => $totalStock,
                'reports' => $reports,
                'previous_physical_stocks' => $previousPhysicalStocks,
            ]
        ], 200, ['Cache-Control' => 'no-cache']);
    }

    /**
     * Update physical stock for a product
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhysicalStock(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
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
            $brand = explode(' ', trim($product->name))[0];
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

            if ($physicalStock == 0) {
                $product->productUnits()->delete();
                foreach ($product->productUnits()->pluck('unit_code')->toArray() as $unitCode) {
                    Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
                }
                Cache::forget($this->getProductCacheKey($product->id));
                $product->delete();
                $stockMessage = 'Produk dihapus karena stok fisik 0.';
                $stockMismatch['message'] = 'Produk dihapus karena stok fisik 0.';
                $this->clearSearchCaches();
            } elseif ($difference > 0) {
                $stockMessage = "Stok fisik: {$physicalStock}, Stok sistem: {$currentStock}, Selisih: +{$difference} unit";
                $stockMismatch['message'] = "Stok tidak sesuai, lebih {$difference} unit";
                for ($i = $currentStock; $i < $physicalStock; $i++) {
                    $unitCode = 'UNIT-' . strtoupper(Str::random(12));
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
                }
                $this->clearSearchCaches();
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
                }
                $this->clearSearchCaches();
            } else {
                $stockMessage = "Stok fisik sesuai dengan stok sistem: {$physicalStock} unit";
                $stockMismatch['message'] = "Stok sesuai dengan sistem";
            }

            Cache::forever($this->getProductCacheKey($product->id), [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $brand,
                'model' => $model,
                'size' => $product->size,
                'color' => $product->color,
                'selling_price' => $product->selling_price,
                'discount_price' => $product->discount_price,
                'stock' => $physicalStock,
            ]);

            $existingMismatches = Cache::get('stock_mismatches', []);
            $existingMismatches[$product->id] = $stockMismatch;
            Cache::forever('stock_mismatches', $existingMismatches);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $stockMessage,
                'data' => [
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'brand' => $brand,
                        'model' => $model,
                        'physical_stock' => $physicalStock,
                        'system_stock' => $currentStock,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat stok fisik: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save stock opname reports
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

            $existingReports = [];
            foreach ($reports as $report) {
                $existingReports[$report['product_id']] = $report;
            }

            foreach ($validated['reports'] as $report) {
                $product = Product::find($report['product_id']);
                $scannedQRCodes = $report['scanned_qr_codes'] ?? [];

                $allUnitCodes = [];
                if ($product) {
                    $allUnitCodes = ProductUnit::where('product_id', $report['product_id'])
                        ->where('is_active', true)
                        ->pluck('unit_code')
                        ->toArray();
                }

                $unscannedQRCodes = array_diff($allUnitCodes, array_map(function($qr) {
                    return basename(parse_url($qr, PHP_URL_PATH));
                }, $scannedQRCodes));

                if ($report['physical_stock'] == 0 && $product) {
                    DB::beginTransaction();
                    try {
                        $product->productUnits()->delete();
                        foreach ($product->productUnits()->pluck('unit_code')->toArray() as $unitCode) {
                            Cache::forget($this->getUnitCacheKey($product->id, $unitCode));
                        }
                        Cache::forget($this->getProductCacheKey($product->id));
                        $product->delete();
                        $this->clearSearchCaches();
                        DB::commit();
                        unset($existingReports[$report['product_id']]);
                        continue;
                    } catch (\Exception $e) {
                        DB::rollBack();
                        continue;
                    }
                }

                $brand = $report['name'] ? explode(' ', trim($report['name']))[0] : 'Unknown';
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
                    'timestamp' => now('Asia/Jakarta')->toDateTimeString(),
                ];
            }

            $reports = array_values($existingReports);
            Cache::forever('stock_opname_reports', $reports);
            $this->clearSearchCaches();

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

    /**
     * Delete a specific stock opname report
     *
     * @param int $index
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReport($index)
    {
        try {
            $reports = Cache::get('stock_opname_reports', []);
            if (isset($reports[$index])) {
                unset($reports[$index]);
                $reports = array_values($reports);
                Cache::forever('stock_opname_reports', $reports);
                $this->clearSearchCaches();
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil dihapus',
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus laporan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete all stock opname reports
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAllReports()
    {
        try {
            Cache::forget('stock_opname_reports');
            Cache::forget('stock_mismatches');
            $this->clearSearchCaches();
            return response()->json([
                'success' => true,
                'message' => 'Semua laporan berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus semua laporan: ' . $e->getMessage(),
            ], 500);
        }
    }
}