<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\ProductHistory;
use App\Models\StockOpnameReport;
use App\Models\PurchaseNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    private function getProductCacheKey($id): string
    {
        return "product_{$id}";
    }

    private function getUnitCacheKey($productId, $unitCode): string
    {
        return "unit_{$productId}_{$unitCode}";
    }

    private function getUserCacheKey(string $key): string
    {
        $userId = Auth::id() ?? 'guest';
        return "user_{$userId}_{$key}";
    }

    private function getLowStockThreshold(): int
    {
        return Cache::get($this->getUserCacheKey('low_stock_threshold'), 5);
    }

    public function setLowStockThreshold(Request $request)
    {
        $request->validate([
            'low_stock_threshold' => 'required|integer|min:1|max:1000',
        ]);

        $threshold = $request->input('low_stock_threshold');
        Cache::forever($this->getUserCacheKey('low_stock_threshold'), $threshold);

        return response()->json([
            'success'   => true,
            'message'   => "Batas stok menipis berhasil diatur menjadi {$threshold} unit.",
            'threshold' => $threshold
        ]);
    }

    public function index()
    {
        $lowStockThreshold = $this->getLowStockThreshold();

        $products = Product::withCount(['productUnits' => fn($q) => $q->where('is_active', true)])
            ->whereHas('productUnits', fn($q) => $q->where('is_active', true))
            ->orderBy('updated_at', 'desc')
            ->orderBy('name')
            ->orderBy('size')
            ->paginate(12);

        // Statistik global (cache terpisah)
        $statsCacheKey = $this->getUserCacheKey('global_inventory_stats_v2');

        $stats = Cache::remember($statsCacheKey, now()->addMinutes(15), function () use ($lowStockThreshold) {
            $totalProducts = Product::whereHas('productUnits', fn($q) => $q->where('is_active', true))->count();
            $totalStock    = ProductUnit::where('is_active', true)->count();

            $lowStockProducts = Product::whereHas('productUnits', function ($q) use ($lowStockThreshold) {
                $q->where('is_active', true)
                  ->groupBy('product_id')
                  ->havingRaw('COUNT(*) < ?', [$lowStockThreshold]);
            })->count();

            return compact('totalProducts', 'totalStock', 'lowStockProducts');
        });

        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);

        $allBrandCounts = Cache::remember(
            $this->getUserCacheKey('all_brand_counts_v2'),
            now()->addHours(4),
            function () use ($brandNames) {
                return Product::whereHas('productUnits', fn($q) => $q->where('is_active', true))
                    ->withCount(['productUnits' => fn($q) => $q->where('is_active', true)])
                    ->get()
                    ->groupBy(fn($p) => $brandNames[$p->id] ?? Str::lower(explode(' ', trim($p->name))[0] ?? 'unknown'))
                    ->map->sum('product_units_count')
                    ->sortDesc();
            }
        );

        $newProducts     = Cache::get($this->getUserCacheKey('new_products'), []);
        $updatedProducts = Cache::get($this->getUserCacheKey('updated_products'), []);

        // Variabel filter default untuk view index (agar tidak error undefined variable)
        $searchTerm     = '';
        $sizeTerm       = '';
        $brandFilter    = request('brand', '');          // support query string ?brand= jika ada
        $lowStockFilter = request()->boolean('low_stock', false);

        return view('inventory.index', array_merge(
            compact(
                'products',
                'allBrandCounts',
                'newProducts',
                'updatedProducts',
                'lowStockThreshold',
                'searchTerm',
                'sizeTerm',
                'brandFilter',
                'lowStockFilter'
            ),
            $stats
        ));
    }

    public function search(Request $request)
    {
        $searchTerm     = trim($request->input('search', ''));
        $sizeTerm       = trim($request->input('size', ''));
        $brandFilter    = trim($request->input('brand', ''));
        $lowStockFilter = $request->boolean('low_stock', false);
        $page           = max(1, (int) $request->input('page', 1));

        $lowStockThreshold = $this->getLowStockThreshold();

        $cacheKeyParts = [
            'v'            => 4,
            'search'       => $searchTerm,
            'size'         => $sizeTerm,
            'brand'        => $brandFilter,
            'low_stock'    => $lowStockFilter ? 1 : 0,
            'threshold'    => $lowStockThreshold,
            'page'         => $page,
        ];

        $cacheKey = 'inventory_search:' . md5(json_encode($cacheKeyParts, JSON_UNESCAPED_SLASHES));

        $products = Cache::remember($cacheKey, now()->addMinutes(10), function () use (
            $searchTerm,
            $sizeTerm,
            $brandFilter,
            $lowStockFilter,
            $lowStockThreshold,
            $page,
            $request
        ) {
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);

            $query = Product::withCount(['productUnits' => fn($q) => $q->where('is_active', true)])
                ->whereHas('productUnits', function ($q) use ($lowStockFilter, $lowStockThreshold) {
                    $q->where('is_active', true);
                    if ($lowStockFilter) {
                        $q->groupBy('product_id')
                          ->havingRaw('COUNT(*) < ?', [$lowStockThreshold]);
                    }
                })
                ->when($searchTerm !== '', fn($q) => $q->where(function ($sq) use ($searchTerm) {
                    $sq->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                       ->orWhereRaw('LOWER(color) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                }))
                ->when($sizeTerm !== '', fn($q) => $q->whereRaw('LOWER(size) LIKE ?', ['%' . strtolower($sizeTerm) . '%']));

            // Filter brand - prioritas exact match dari cache brand_names
            if ($brandFilter !== '') {
                $matchingProductIds = collect($brandNames)
                    ->filter(fn($b) => strtolower($b) === strtolower($brandFilter))
                    ->keys()
                    ->toArray();

                if (!empty($matchingProductIds)) {
                    $query->whereIn('id', $matchingProductIds);
                } else {
                    // Fallback: jika brand tidak ada di cache, cari di awal nama
                    $query->whereRaw('LOWER(name) LIKE ?', [strtolower($brandFilter) . '%']);
                }
            }

            return $query->orderBy('updated_at', 'desc')
                         ->orderBy('name')
                         ->orderBy('size')
                         ->paginate(12)
                         ->appends($request->query());
        });

        // Statistik global (cache terpisah)
        $statsCacheKey = $this->getUserCacheKey('global_inventory_stats_v2');

        $stats = Cache::remember($statsCacheKey, now()->addMinutes(15), function () use ($lowStockThreshold) {
            $totalProducts = Product::whereHas('productUnits', fn($q) => $q->where('is_active', true))->count();
            $totalStock    = ProductUnit::where('is_active', true)->count();

            $lowStockProducts = Product::whereHas('productUnits', function ($q) use ($lowStockThreshold) {
                $q->where('is_active', true)
                  ->groupBy('product_id')
                  ->havingRaw('COUNT(*) < ?', [$lowStockThreshold]);
            })->count();

            return compact('totalProducts', 'totalStock', 'lowStockProducts');
        });

        $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);

        $allBrandCounts = Cache::remember(
            $this->getUserCacheKey('all_brand_counts_v2'),
            now()->addHours(4),
            function () use ($brandNames) {
                return Product::whereHas('productUnits', fn($q) => $q->where('is_active', true))
                    ->withCount(['productUnits' => fn($q) => $q->where('is_active', true)])
                    ->get()
                    ->groupBy(fn($p) => $brandNames[$p->id] ?? Str::lower(explode(' ', trim($p->name))[0] ?? 'unknown'))
                    ->map->sum('product_units_count')
                    ->sortDesc();
            }
        );

        $newProducts     = Cache::get($this->getUserCacheKey('new_products'), []);
        $updatedProducts = Cache::get($this->getUserCacheKey('updated_products'), []);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'products' => collect($products->items())->map(function ($product) use ($brandNames) {
                    $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0] ?? 'Unknown';
                    $model = trim(str_replace($brand, '', $product->name));

                    return [
                        'id'             => $product->id,
                        'brand'          => ucfirst(strtolower($brand)),
                        'model'          => $model,
                        'name'           => $product->name,
                        'size'           => $product->size ?? '-',
                        'color'          => $product->color ?? '-',
                        'selling_price'  => (int) $product->selling_price,
                        'discount_price' => $product->discount_price ? (int) $product->discount_price : null,
                        'stock'          => (int) $product->product_units_count,
                    ];
                })->values(),

                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page'    => $products->lastPage(),
                    'total'        => $products->total(),
                    'per_page'     => $products->perPage(),
                    'from'         => $products->firstItem(),
                    'to'           => $products->lastItem(),
                ],

                'totalProducts'    => $stats['totalProducts'],
                'totalStock'       => $stats['totalStock'],
                'lowStockProducts' => $stats['lowStockProducts'],
                'allBrandCounts'   => $allBrandCounts->toArray(),
                'lowStockThreshold'=> $lowStockThreshold,

                'active_filters'   => [
                    'search'     => $searchTerm ?: null,
                    'size'       => $sizeTerm ?: null,
                    'brand'      => $brandFilter ?: null,
                    'low_stock'  => $lowStockFilter,
                ],
            ]);
        }

        return view('inventory.index', array_merge(
            compact(
                'products',
                'allBrandCounts',
                'lowStockThreshold',
                'searchTerm',
                'sizeTerm',
                'brandFilter',
                'lowStockFilter'
            ),
            $stats,
            [
                'newProducts'     => $newProducts,
                'updatedProducts' => $updatedProducts,
            ]
        ));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand'          => 'required|string|max:255',
            'model'          => 'required|string|max:255',
            'color'          => 'required|string|max:255',
            'sizes'          => 'required|array|min:1',
            'sizes.*.size'   => 'required|string|max:50',
            'sizes.*.stock'  => 'required|integer|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:selling_price',
        ]);

        $totalStock = array_sum(array_column($validated['sizes'], 'stock'));
        if ($totalStock == 0) {
            return redirect()->route('inventory.index')
                ->with('success', 'Produk tidak disimpan karena stok 0.');
        }

        DB::beginTransaction();
        try {
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
            $userId     = Auth::id() ?? 'guest';
            $userName   = Auth::user()?->name ?? 'Unknown';

            foreach ($validated['sizes'] as $sizeData) {
                if ((int) $sizeData['stock'] === 0) {
                    continue;
                }

                $product = Product::create([
                    'name'           => trim($validated['brand'] . ' ' . $validated['model']),
                    'size'           => $sizeData['size'],
                    'color'          => $validated['color'],
                    'selling_price'  => $validated['selling_price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                ]);

                $brandNames[$product->id] = $validated['brand'];
                Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

                PurchaseNote::create([
                    'user_id'        => $userId,
                    'type'           => 'masuk',
                    'product_name'   => $product->name,
                    'size'           => $product->size,
                    'color'          => $product->color,
                    'original_price' => $validated['selling_price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                    'quantity'       => (int) $sizeData['stock'],
                ]);

                ProductHistory::create([
                    'type'         => 'masuk',
                    'product_id'   => $product->id,
                    'brand'        => $validated['brand'],
                    'model'        => $validated['model'],
                    'size'         => $sizeData['size'],
                    'color'        => $validated['color'],
                    'stock'        => (int) $sizeData['stock'],
                    'stock_change' => "+{$sizeData['stock']} unit",
                    'selling_price'  => $validated['selling_price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                    'user_id'      => $userId,
                    'user_name'    => $userName,
                    'timestamp'    => Carbon::now('Asia/Jakarta'),
                ]);

                $productNewUnitCodes = [];
                for ($i = 0; $i < (int) $sizeData['stock']; $i++) {
                    do {
                        $unitCode = 'UNIT-' . strtoupper(Str::random(8));
                    } while (ProductUnit::where('unit_code', $unitCode)->exists());

                    $unit = ProductUnit::create([
                        'product_id' => $product->id,
                        'unit_code'  => $unitCode,
                        'qr_code'    => '',
                        'is_active'  => true,
                    ]);

                    $qrCode = route('inventory.show_unit', [
                        'product'   => $product->id,
                        'unitCode'  => $unit->unit_code
                    ]);

                    $unit->update(['qr_code' => $qrCode]);
                    $productNewUnitCodes[] = $unit->unit_code;
                }

                Cache::forever($this->getUserCacheKey('new_units_' . $product->id), $productNewUnitCodes);
                Log::info("Stored new units for product ID {$product->id}: " . json_encode($productNewUnitCodes));
            }

            DB::commit();
            return redirect()->route('inventory.index')
                ->with('success', 'Produk dan unit berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store product: ' . $e->getMessage());
            return redirect()->route('inventory.index')
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $product = Product::with(['productUnits' => fn($q) => $q->where('is_active', true)])->find($id);

        if (!$product) {
            $cachedProduct = Cache::get($this->getProductCacheKey($id));
            if ($cachedProduct) {
                $product = (object) $cachedProduct;
                $product->productUnits = collect([]);
            } else {
                $product = (object) [
                    'id'             => $id,
                    'name'           => 'Produk Tidak Ditemukan',
                    'size'           => 'N/A',
                    'color'          => 'N/A',
                    'selling_price'  => 0,
                    'discount_price' => null,
                    'stock'          => 0,
                    'productUnits'   => collect([]),
                ];
            }
        }

        return view('inventory.show', compact('product'));
    }

    public function showUnit($productId, $unitCode)
    {
        $product = Product::with(['productUnits' => fn($q) => $q->where('is_active', true)])->find($productId);
        $unit = ProductUnit::where('product_id', $productId)->where('unit_code', $unitCode)->first();
        $similarProducts = [];

        if ($product && $product->name !== 'Produk Tidak Ditemukan') {
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
            $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0] ?? 'Unknown';

            $similarProducts = Product::withCount(['productUnits' => fn($q) => $q->where('is_active', true)])
                ->where('name', 'like', $brand . '%')
                ->where('id', '!=', $productId)
                ->whereHas('productUnits', fn($q) => $q->where('is_active', true))
                ->get()
                ->map(function ($similarProduct) use ($brandNames) {
                    $sBrand = $brandNames[$similarProduct->id] ?? 'Unknown';
                    $sModel = $sBrand !== 'Unknown' ? trim(str_replace($sBrand, '', $similarProduct->name)) : $similarProduct->name;
                    return [
                        'id'             => $similarProduct->id,
                        'brand'          => $sBrand,
                        'model'          => $sModel,
                        'name'           => $similarProduct->name,
                        'size'           => $similarProduct->size,
                        'color'          => $similarProduct->color,
                        'stock'          => $similarProduct->product_units_count,
                        'selling_price'  => $similarProduct->selling_price,
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
                    'id'             => $productId,
                    'name'           => 'Produk Tidak Ditemukan',
                    'size'           => 'N/A',
                    'color'          => 'N/A',
                    'selling_price'  => 0,
                    'discount_price' => null,
                    'productUnits'   => collect([]),
                ];
                $unit = (object) [
                    'unit_code' => $unitCode,
                    'qr_code'   => route('inventory.show_unit', ['product' => $productId, 'unitCode' => $unitCode]),
                    'is_active' => false,
                ];
                $similarProducts = [];
            }
        }

        return view('inventory.show_unit', compact('product', 'unit', 'similarProducts'));
    }

    public function json($id)
    {
        $product = Product::with(['productUnits' => fn($q) => $q->where('is_active', true)])->find($id);

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

        $cachedBrand = $brandNames[$product->id] ?? null;
        if ($cachedBrand) {
            $replaced = str_replace($cachedBrand, '', $product->name, $count);
            $model = $count > 0 ? trim($replaced) : $product->name;
            $brand = $cachedBrand;
        } else {
            $nameParts = explode(' ', trim($product->name));
            $brand = $nameParts[0] ?? 'Unknown';
            $model = trim(implode(' ', array_slice($nameParts, 1))) ?: $product->name;
        }

        $brand = $brand && $brand !== '' ? ucfirst(strtolower($brand)) : 'Unknown';

        return response()->json([
            'id'             => $product->id,
            'brand'          => $brand,
            'model'          => $model,
            'name'           => $product->name,
            'color'          => $product->color ?? 'N/A',
            'size'           => $product->size ?? 'N/A',
            'selling_price'  => (int) ($product->selling_price ?? 0),
            'discount_price' => $product->discount_price ? (int) $product->discount_price : null,
            'stock'          => $product->productUnits->count(),
            'units'          => $product->productUnits->map(fn($unit) => [
                'unit_code' => $unit->unit_code,
                'qr_code'   => $unit->qr_code,
                'is_active' => $unit->is_active,
            ])->values(),
        ], 200);
    }

    public function edit($id)
    {
        $product = Product::with(['productUnits' => fn($q) => $q->where('is_active', true)])->find($id);
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
            'brand'          => 'required|string|max:255',
            'model'          => 'required|string|max:255',
            'color'          => 'required|string|max:255',
            'sizes'          => 'required|array|min:1',
            'sizes.*.size'   => 'required|string|max:50',
            'sizes.*.stock'  => 'required|integer|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:selling_price',
        ]);

        $totalStock = array_sum(array_column($validated['sizes'], 'stock'));

        if ($totalStock == 0) {
            DB::beginTransaction();
            try {
                $product->productUnits()->delete();
                $product->delete();

                $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
                unset($brandNames[$product->id]);
                Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

                ProductHistory::create([
                    'type'         => 'keluar',
                    'product_id'   => $product->id,
                    'brand'        => $validated['brand'],
                    'model'        => $validated['model'],
                    'size'         => $product->size,
                    'color'        => $product->color,
                    'stock'        => 0,
                    'stock_change' => '0 unit',
                    'selling_price'  => $product->selling_price,
                    'discount_price' => $product->discount_price ?? null,
                    'user_id'      => Auth::id() ?? 'guest',
                    'user_name'    => Auth::user()?->name ?? 'Unknown',
                    'timestamp'    => Carbon::now('Asia/Jakarta'),
                ]);

                DB::commit();
                return redirect()->route('inventory.index')
                    ->with('success', 'Produk dihapus karena stok 0.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to delete product due to zero stock: ' . $e->getMessage());
                return redirect()->route('inventory.index')
                    ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
            }
        }

        DB::beginTransaction();
        try {
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
            $userId     = Auth::id() ?? 'guest';
            $userName   = Auth::user()?->name ?? 'Unknown';

            $currentStock = $product->productUnits()->where('is_active', true)->count();
            $desiredStock = (int) $validated['sizes'][0]['stock'];
            $stockChange = $desiredStock - $currentStock;
            $stockChangeDescription = $stockChange > 0 ? "+{$stockChange} unit" : ($stockChange < 0 ? "-" . abs($stockChange) . " unit" : "tidak berubah");

            ProductHistory::create([
                'type'         => 'perbarui',
                'product_id'   => $product->id,
                'brand'        => $validated['brand'],
                'model'        => $validated['model'],
                'size'         => $validated['sizes'][0]['size'],
                'color'        => $validated['color'],
                'stock'        => $desiredStock,
                'stock_change' => $stockChangeDescription,
                'selling_price'  => $validated['selling_price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'user_id'      => $userId,
                'user_name'    => $userName,
                'timestamp'    => Carbon::now('Asia/Jakarta'),
            ]);

            $product->update([
                'name'           => trim($validated['brand'] . ' ' . $validated['model']),
                'size'           => $validated['sizes'][0]['size'],
                'color'          => $validated['color'],
                'selling_price'  => $validated['selling_price'],
                'discount_price' => $validated['discount_price'] ?? null,
            ]);

            $brandNames[$product->id] = $validated['brand'];
            Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

            $newUnitCodes = Cache::get($this->getUserCacheKey('new_units_' . $product->id), []) ?? [];

            if ($desiredStock > $currentStock) {
                for ($i = $currentStock; $i < $desiredStock; $i++) {
                    do {
                        $unitCode = 'UNIT-' . strtoupper(Str::random(8));
                    } while (ProductUnit::where('unit_code', $unitCode)->exists());

                    $unit = ProductUnit::create([
                        'product_id' => $product->id,
                        'unit_code'  => $unitCode,
                        'qr_code'    => '',
                        'is_active'  => true,
                    ]);

                    $qrCode = route('inventory.show_unit', [
                        'product'   => $product->id,
                        'unitCode'  => $unit->unit_code
                    ]);

                    $unit->update(['qr_code' => $qrCode]);
                    $newUnitCodes[] = $unit->unit_code;
                }
            } elseif ($desiredStock < $currentStock) {
                $unitsToDeactivate = $product->productUnits()
                    ->where('is_active', true)
                    ->take($currentStock - $desiredStock)
                    ->get();

                foreach ($unitsToDeactivate as $unit) {
                    $unit->update(['is_active' => false]);
                    $newUnitCodes = array_diff($newUnitCodes, [$unit->unit_code]);
                }
            }

            // Proses size tambahan
            for ($i = 1; $i < count($validated['sizes']); $i++) {
                $sizeData = $validated['sizes'][$i];
                if ((int) $sizeData['stock'] === 0) {
                    continue;
                }

                $newProduct = Product::create([
                    'name'           => trim($validated['brand'] . ' ' . $validated['model']),
                    'size'           => $sizeData['size'],
                    'color'          => $validated['color'],
                    'selling_price'  => $validated['selling_price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                ]);

                $brandNames[$newProduct->id] = $validated['brand'];
                Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

                ProductHistory::create([
                    'type'         => 'masuk',
                    'product_id'   => $newProduct->id,
                    'brand'        => $validated['brand'],
                    'model'        => $validated['model'],
                    'size'         => $sizeData['size'],
                    'color'        => $validated['color'],
                    'stock'        => (int) $sizeData['stock'],
                    'stock_change' => "+{$sizeData['stock']} unit",
                    'selling_price'  => $validated['selling_price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                    'user_id'      => $userId,
                    'user_name'    => $userName,
                    'timestamp'    => Carbon::now('Asia/Jakarta'),
                ]);

                $productNewUnitCodes = [];
                for ($j = 0; $j < (int) $sizeData['stock']; $j++) {
                    do {
                        $unitCode = 'UNIT-' . strtoupper(Str::random(8));
                    } while (ProductUnit::where('unit_code', $unitCode)->exists());

                    $unit = ProductUnit::create([
                        'product_id' => $newProduct->id,
                        'unit_code'  => $unitCode,
                        'qr_code'    => '',
                        'is_active'  => true,
                    ]);

                    $qrCode = route('inventory.show_unit', [
                        'product'   => $newProduct->id,
                        'unitCode'  => $unit->unit_code
                    ]);

                    $unit->update(['qr_code' => $qrCode]);
                    $productNewUnitCodes[] = $unit->unit_code;
                }

                Cache::forever($this->getUserCacheKey('new_units_' . $newProduct->id), $productNewUnitCodes);
            }

            Cache::forever($this->getUserCacheKey('new_units_' . $product->id), $newUnitCodes);

            DB::commit();
            return redirect()->route('inventory.index')
                ->with('success', 'Produk dan unit berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update product: ' . $e->getMessage());
            return redirect()->route('inventory.index')
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
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
            $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0] ?? 'Unknown';
            $model = trim(str_replace($brand, '', $product->name));

            $stock = $product->productUnits()->where('is_active', true)->count();

            ProductHistory::create([
                'type'         => 'keluar',
                'product_id'   => $product->id,
                'brand'        => $brand,
                'model'        => $model,
                'size'         => $product->size,
                'color'        => $product->color,
                'stock'        => $stock,
                'stock_change' => "-{$stock} unit",
                'selling_price'  => $product->selling_price,
                'discount_price' => $product->discount_price ?? null,
                'user_id'      => Auth::id() ?? 'guest',
                'user_name'    => Auth::user()?->name ?? 'Unknown',
                'timestamp'    => Carbon::now('Asia/Jakarta'),
            ]);

            $product->productUnits()->delete();
            $product->delete();

            unset($brandNames[$product->id]);
            Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

            DB::commit();
            return redirect()->route('inventory.index')
                ->with('success', 'Produk dan unit berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete product: ' . $e->getMessage());
            return redirect()->route('inventory.index')
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function printQr($id)
    {
        $product = Product::with(['productUnits' => fn($q) => $q->where('is_active', true)])->find($id);

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

        if (!empty($newUnitCodes)) {
            $product->productUnits = $product->productUnits->whereIn('unit_code', $newUnitCodes);
        }

        Cache::forget($this->getUserCacheKey('new_units_' . $id));

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
                    'id'                 => $report->id,
                    'product_id'         => $report->product_id,
                    'name'               => $report->name,
                    'brand'              => $report->brand,
                    'model'              => $report->model,
                    'size'               => $report->size,
                    'color'              => $report->color,
                    'system_stock'       => $report->system_stock,
                    'physical_stock'     => $report->physical_stock,
                    'difference'         => $report->difference,
                    'scanned_qr_codes'   => $report->scanned_qr_codes,
                    'unscanned_qr_codes' => $report->unscanned_qr_codes,
                    'timestamp'          => $report->timestamp->toDateTimeString(),
                ];
            })->toArray();

        $previousPhysicalStocks = StockOpnameReport::pluck('physical_stock', 'product_id')->toArray();

        return view('inventory.stock_opname', compact('totalStock', 'reports', 'previousPhysicalStocks'));
    }

    public function validateQrCode(Request $request)
    {
        try {
            $request->validate(['qr_code' => 'required|string']);
            $qrCode = $request->input('qr_code');

            $exists = StockOpnameReport::whereJsonContains('scanned_qr_codes', $qrCode)->exists();
            if ($exists) {
                return response()->json(['valid' => false, 'message' => 'QR code sudah ada di laporan sebelumnya.'], 422);
            }

            if (!str_contains($qrCode, 'inventory')) {
                return response()->json(['valid' => false, 'message' => 'Format QR code tidak valid.'], 422);
            }

            $urlParts = explode('/', $qrCode);
            $productIndex = array_search('inventory', $urlParts);
            if ($productIndex === false || !isset($urlParts[$productIndex + 1])) {
                return response()->json(['valid' => false, 'message' => 'Tidak dapat menemukan ID produk.'], 422);
            }

            $productId = $urlParts[$productIndex + 1];
            if (!ctype_digit($productId)) {
                return response()->json(['valid' => false, 'message' => 'ID produk tidak valid.'], 422);
            }

            $product = Product::find($productId);
            if (!$product) {
                return response()->json(['valid' => false, 'message' => 'Produk tidak ditemukan.'], 404);
            }

            $unitCode = basename(parse_url($qrCode, PHP_URL_PATH));
            $unitExists = ProductUnit::where('product_id', $productId)
                ->where('unit_code', $unitCode)
                ->where('is_active', true)
                ->exists();

            if (!$unitExists) {
                return response()->json(['valid' => false, 'message' => 'Unit tidak ditemukan atau tidak aktif.'], 404);
            }

            return response()->json(['valid' => true, 'product_id' => $productId, 'unit_code' => $unitCode]);
        } catch (\Exception $e) {
            Log::error('Error validating QR code: ' . $e->getMessage());
            return response()->json(['valid' => false, 'message' => 'Gagal memvalidasi QR code.'], 500);
        }
    }

    public function updatePhysicalStock(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $validated = $request->validate(['physical_stock' => 'required|integer|min:0']);
        $physicalStock = (int) $validated['physical_stock'];
        $currentStock = $product->productUnits()->where('is_active', true)->count();
        $difference = $physicalStock - $currentStock;

        DB::beginTransaction();
        try {
            $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
            $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0] ?? 'Unknown';
            $model = trim(str_replace($brand, '', $product->name));

            $newUnitCodes = Cache::get($this->getUserCacheKey('new_units_' . $product->id), []) ?? [];

            if ($physicalStock == 0) {
                $product->productUnits()->delete();
                $product->delete();

                unset($brandNames[$product->id]);
                Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

                ProductHistory::create([
                    'type'         => 'keluar',
                    'product_id'   => $product->id,
                    'brand'        => $brand,
                    'model'        => $model,
                    'size'         => $product->size,
                    'color'        => $product->color,
                    'stock'        => 0,
                    'stock_change' => "-{$currentStock} unit",
                    'selling_price'  => $product->selling_price,
                    'discount_price' => $product->discount_price ?? null,
                    'user_id'      => Auth::id() ?? 'guest',
                    'user_name'    => Auth::user()?->name ?? 'Unknown',
                    'timestamp'    => Carbon::now('Asia/Jakarta'),
                ]);
            } elseif ($difference > 0) {
                for ($i = $currentStock; $i < $physicalStock; $i++) {
                    do {
                        $unitCode = 'UNIT-' . strtoupper(Str::random(12));
                    } while (ProductUnit::where('unit_code', $unitCode)->exists());

                    $unit = ProductUnit::create([
                        'product_id' => $product->id,
                        'unit_code'  => $unitCode,
                        'qr_code'    => '',
                        'is_active'  => true,
                    ]);

                    $qrCode = route('inventory.show_unit', [
                        'product'   => $product->id,
                        'unitCode'  => $unit->unit_code
                    ]);

                    $unit->update(['qr_code' => $qrCode]);
                    $newUnitCodes[] = $unit->unit_code;

                    ProductHistory::create([
                        'type'         => 'masuk',
                        'product_id'   => $product->id,
                        'brand'        => $brand,
                        'model'        => $model,
                        'size'         => $product->size,
                        'color'        => $product->color,
                        'stock'        => $physicalStock,
                        'stock_change' => '+1 unit',
                        'selling_price'  => $product->selling_price,
                        'discount_price' => $product->discount_price ?? null,
                        'user_id'      => Auth::id() ?? 'guest',
                        'user_name'    => Auth::user()?->name ?? 'Unknown',
                        'timestamp'    => Carbon::now('Asia/Jakarta'),
                    ]);
                }
            } elseif ($difference < 0) {
                $unitsToDeactivate = $product->productUnits()
                    ->where('is_active', true)
                    ->take(abs($difference))
                    ->get();

                foreach ($unitsToDeactivate as $unit) {
                    $unit->update(['is_active' => false]);
                    $newUnitCodes = array_diff($newUnitCodes, [$unit->unit_code]);

                    ProductHistory::create([
                        'type'         => 'keluar',
                        'product_id'   => $product->id,
                        'brand'        => $brand,
                        'model'        => $model,
                        'size'         => $product->size,
                        'color'        => $product->color,
                        'stock'        => $physicalStock,
                        'stock_change' => '-1 unit',
                        'selling_price'  => $product->selling_price,
                        'discount_price' => $product->discount_price ?? null,
                        'user_id'      => Auth::id() ?? 'guest',
                        'user_name'    => Auth::user()?->name ?? 'Unknown',
                        'timestamp'    => Carbon::now('Asia/Jakarta'),
                    ]);
                }
            }

            Cache::forever($this->getUserCacheKey('new_units_' . $product->id), $newUnitCodes);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $difference == 0 ? "Stok sesuai sistem." : "Selisih stok: " . ($difference > 0 ? '+' : '') . $difference . " unit",
                'product' => [
                    'id'            => $product->id,
                    'name'          => $product->name,
                    'brand'         => $brand,
                    'model'         => $model,
                    'physical_stock' => $physicalStock,
                    'system_stock'  => $currentStock,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update physical stock: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mencatat stok fisik.'], 500);
        }
    }

    public function saveReport(Request $request)
    {
        $validated = $request->validate([
            'reports' => 'required|array',
            'reports.*.product_id'         => 'required|integer',
            'reports.*.name'               => 'nullable|string',
            'reports.*.size'               => 'nullable|string',
            'reports.*.color'              => 'nullable|string',
            'reports.*.system_stock'       => 'required|integer',
            'reports.*.physical_stock'     => 'required|integer',
            'reports.*.difference'         => 'required|integer',
            'reports.*.scanned_qr_codes'   => 'nullable|array',
            'reports.*.scanned_qr_codes.*' => 'string',
        ]);

        DB::beginTransaction();
        try {
            $skippedQRCodes = [];

            foreach ($validated['reports'] as $reportData) {
                $productId = $reportData['product_id'];
                $scannedQRCodes = $reportData['scanned_qr_codes'] ?? [];

                $latestReport = StockOpnameReport::where('product_id', $productId)
                    ->orderBy('timestamp', 'desc')
                    ->first();

                $brand = $reportData['name'] ? explode(' ', trim($reportData['name']))[0] ?? 'Unknown' : 'Unknown';
                $model = $reportData['name'] ? trim(str_replace($brand, '', $reportData['name'])) : 'Unknown';

                $product = Product::find($productId);

                $existingQRCodes = StockOpnameReport::where('product_id', $productId)
                    ->whereNotNull('scanned_qr_codes')
                    ->pluck('scanned_qr_codes')
                    ->flatten()
                    ->map(fn($qr) => basename(parse_url($qr, PHP_URL_PATH)))
                    ->toArray();

                $filteredScannedQRCodes = array_filter($scannedQRCodes, function ($qrCode) use ($existingQRCodes, &$skippedQRCodes) {
                    $unitCode = basename(parse_url($qrCode, PHP_URL_PATH));
                    if (in_array($unitCode, $existingQRCodes)) {
                        $skippedQRCodes[] = $unitCode;
                        return false;
                    }
                    return true;
                });

                if (empty($filteredScannedQRCodes) && !empty($scannedQRCodes)) {
                    continue;
                }

                $allUnitCodes = $product ? ProductUnit::where('product_id', $productId)
                    ->where('is_active', true)
                    ->pluck('unit_code')
                    ->toArray() : [];

                $unscannedQRCodes = array_diff(
                    $allUnitCodes,
                    array_map(fn($qr) => basename(parse_url($qr, PHP_URL_PATH)), $filteredScannedQRCodes)
                );

                if ($reportData['physical_stock'] == 0 && $product) {
                    $product->productUnits()->delete();
                    $product->delete();

                    $brandNames = Cache::get($this->getUserCacheKey('brand_names'), []);
                    unset($brandNames[$product->id]);
                    Cache::forever($this->getUserCacheKey('brand_names'), $brandNames);

                    ProductHistory::create([
                        'type'         => 'keluar',
                        'product_id'   => $product->id,
                        'brand'        => $brand,
                        'model'        => $model,
                        'size'         => $product->size,
                        'color'        => $product->color,
                        'stock'        => 0,
                        'stock_change' => "-{$reportData['system_stock']} unit",
                        'selling_price'  => $product->selling_price,
                        'discount_price' => $product->discount_price ?? null,
                        'user_id'      => Auth::id() ?? null,
                        'user_name'    => Auth::user()?->name ?? 'Unknown',
                        'timestamp'    => Carbon::now('Asia/Jakarta'),
                    ]);

                    if ($latestReport) {
                        $latestReport->delete();
                    }
                    continue;
                }

                $reportAttributes = [
                    'name'               => $reportData['name'] ?? 'Produk Tidak Diketahui',
                    'brand'              => $brand,
                    'model'              => $model,
                    'size'               => $reportData['size'] ?? '-',
                    'color'              => $reportData['color'] ?? '-',
                    'system_stock'       => $reportData['system_stock'],
                    'physical_stock'     => $reportData['physical_stock'],
                    'difference'         => $reportData['difference'],
                    'unscanned_qr_codes' => array_values($unscannedQRCodes),
                    'user_id'            => Auth::id() ?? null,
                    'user_name'          => Auth::user()?->name ?? 'Unknown',
                    'timestamp'          => Carbon::now('Asia/Jakarta'),
                ];

                if ($latestReport) {
                    $existingScanned = $latestReport->scanned_qr_codes ?? [];
                    $reportAttributes['scanned_qr_codes'] = array_unique(array_merge($existingScanned, $filteredScannedQRCodes));
                    $latestReport->update($reportAttributes);
                } else {
                    $reportAttributes['product_id'] = $productId;
                    $reportAttributes['scanned_qr_codes'] = $filteredScannedQRCodes;
                    StockOpnameReport::create($reportAttributes);
                }
            }

            DB::commit();

            $message = 'Laporan stock opname berhasil disimpan.';
            if (!empty($skippedQRCodes)) {
                $message .= ' Beberapa QR code di-skip karena sudah ada: ' . implode(', ', array_unique($skippedQRCodes));
            }

            return response()->json(['success' => true, 'message' => $message], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save stock opname report: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan laporan.'], 500);
        }
    }

    public function status()
    {
        $newProducts = Cache::get($this->getUserCacheKey('new_products'), []);
        $brandNames  = Cache::get($this->getUserCacheKey('brand_names'), []);
        return view('inventory.product-status', compact('newProducts', 'brandNames'));
    }

    public function deleteReport($id)
    {
        try {
            $report = StockOpnameReport::findOrFail($id);
            $report->delete();
            return redirect()->route('inventory.stock_opname')
                ->with('success', 'Laporan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Failed to delete report: ' . $e->getMessage());
            return redirect()->route('inventory.stock_opname')
                ->with('error', 'Gagal menghapus laporan.');
        }
    }

    public function deleteAllReports()
    {
        try {
            StockOpnameReport::truncate();
            return redirect()->route('inventory.stock_opname')
                ->with('success', 'Semua laporan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Failed to delete all reports: ' . $e->getMessage());
            return redirect()->route('inventory.stock_opname')
                ->with('error', 'Gagal menghapus semua laporan.');
        }
    }

    public function history(Request $request)
    {
        $time_filter   = $request->input('time_filter', 'all');
        $action_filter = $request->input('action_filter', 'all');
        $search        = trim($request->input('search', ''));
        $perPage       = in_array($request->input('per_page'), ['10', '100', '500', '1000', 'all'])
            ? $request->input('per_page')
            : '10';

        $query = ProductHistory::query()
            ->when($time_filter === 'weekly', fn($q) => $q->where('timestamp', '>=', Carbon::now('Asia/Jakarta')->startOfWeek()))
            ->when($time_filter === 'monthly', fn($q) => $q->where('timestamp', '>=', Carbon::now('Asia/Jakarta')->startOfMonth()))
            ->when($action_filter !== 'all', fn($q) => $q->where('type', $action_filter))
            ->when($search !== '', fn($q) => $q->where(fn($sq) => $sq
                ->whereRaw('LOWER(brand) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(model) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(size) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(color) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(user_name) LIKE ?', ['%' . strtolower($search) . '%'])
            ))
            ->latest('timestamp');

        $filteredHistory = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->appends($request->query());

        return view('inventory.history', compact(
            'filteredHistory',
            'time_filter',
            'action_filter',
            'search',
            'perPage'
        ));
    }
}