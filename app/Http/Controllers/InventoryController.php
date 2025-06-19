<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function index()
    {
        $products = Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->paginate(10);
        $totalProducts = Product::count();
        $lowStockProducts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        }, '<=', 10)->count();
        $totalStock = ProductUnit::where('is_active', true)->count();

        Log::info('Loaded products for inventory index: ' . $products->count());
        return view('inventory.index', compact('products', 'totalProducts', 'lowStockProducts', 'totalStock'));
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
            'size' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:selling_price',
            'purchase_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => trim($validated['brand'] . ' ' . $validated['model']),
                'size' => $validated['size'],
                'color' => $validated['color'],
                'purchase_price' => $validated['purchase_price'],
                'selling_price' => $validated['selling_price'],
                'discount_price' => $validated['discount_price'] ?? null,
            ]);

            $units = [];
            for ($i = 0; $i < $validated['stock']; $i++) {
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
            }

            // Cache product data
            Cache::forever($this->getProductCacheKey($product->id), [
                'id' => $product->id,
                'name' => $product->name,
                'size' => $product->size,
                'color' => $product->color,
                'purchase_price' => $product->purchase_price,
                'selling_price' => $product->selling_price,
                'discount_price' => $product->discount_price,
                'stock' => $validated['stock'],
            ]);

            // Cache unit data
            foreach ($units as $unit) {
                Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                    'product_id' => $product->id,
                    'unit_code' => $unit->unit_code,
                    'qr_code' => $unit->qr_code,
                    'is_active' => $unit->is_active,
                ]);
            }

            Log::info('Product created: ID ' . $product->id . ' with ' . $validated['stock'] . ' units');

            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Produk dan unit berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing product: ' . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Terjadi kesalahan, produk gagal ditambahkan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Try to get product from database
        $product = Product::with(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->find($id);

        if (!$product) {
            // Try to get from cache
            $cachedProduct = Cache::get($this->getProductCacheKey($id));
            if ($cachedProduct) {
                $product = (object) $cachedProduct;
                $product->productUnits = collect([]);
                Log::info('Product loaded from cache: ID ' . $id);
            } else {
                // Fallback data
                $product = (object) [
                    'id' => $id,
                    'name' => 'Produk Tidak Ditemukan',
                    'size' => 'N/A',
                    'color' => 'N/A',
                    'purchase_price' => 0,
                    'selling_price' => 0,
                    'discount_price' => null,
                    'stock' => 0,
                    'productUnits' => collect([]),
                ];
                Log::warning('Product not found for show: ID ' . $id);
            }
        }

        return view('inventory.show', compact('product'));
    }

    public function showUnit($productId, $unitCode)
    {
        // Try to get product from database
        $product = Product::find($productId);
        $unit = ProductUnit::where('product_id', $productId)->where('unit_code', $unitCode)->first();

        if (!$product || !$unit) {
            // Try to get from cache
            $cachedProduct = Cache::get($this->getProductCacheKey($productId));
            $cachedUnit = Cache::get($this->getUnitCacheKey($productId, $unitCode));

            if ($cachedProduct && $cachedUnit) {
                $product = (object) $cachedProduct;
                $unit = (object) $cachedUnit;
                Log::info('Product and unit loaded from cache: Product ID ' . $productId . ', Unit ' . $unitCode);
            } else {
                // Fallback data
                $product = (object) [
                    'id' => $productId,
                    'name' => 'Produk Tidak Ditemukan',
                    'size' => 'N/A',
                    'color' => 'N/A',
                    'selling_price' => 0,
                    'discount_price' => null,
                ];
                $unit = (object) [
                    'unit_code' => $unitCode,
                    'qr_code' => route('inventory.show_unit', ['product' => $productId, 'unitCode' => $unitCode]),
                    'is_active' => false,
                ];
                Log::warning('Unit not found for show: Product ID ' . $productId . ', Unit ' . $unitCode);
            }
        }

        return view('inventory.show_unit', compact('product', 'unit'));
    }

    public function json($id)
    {
        $product = Product::find($id);

        if (!$product) {
            $cachedProduct = Cache::get($this->getProductCacheKey($id));
            if ($cachedProduct) {
                $product = (object) $cachedProduct;
                $product->productUnits = collect([]);
            } else {
                Log::warning('Product not found for JSON: ID ' . $id);
                return response()->json(['error' => 'Produk tidak ditemukan'], 404);
            }
        }

        Log::debug('JSON response for product ID ' . $id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'color' => $product->color,
            'size' => $product->size,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'stock' => $product->stock ?? 0,
            'units' => isset($product->productUnits) ? $product->productUnits->map(function ($unit) {
                return [
                    'unit_code' => $unit->unit_code,
                    'qr_code' => $unit->qr_code,
                    'is_active' => $unit->is_active,
                ];
            }) : [],
        ], 200);
    }

    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            Log::warning('Product not found for edit: ID ' . $id);
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }
        return view('inventory.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            Log::warning('Product not found for update: ID ' . $id);
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:selling_price',
            'color' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'name' => trim($validated['brand'] . ' ' . $validated['model']),
                'size' => $validated['size'],
                'color' => $validated['color'],
                'purchase_price' => $validated['purchase_price'],
                'selling_price' => $validated['selling_price'],
                'discount_price' => $validated['discount_price'] ?? null,
            ]);

            $currentStock = $product->productUnits()->where('is_active', true)->count();
            $desiredStock = $validated['stock'];

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

                    // Cache unit data
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $qrCode,
                        'is_active' => $unit->is_active,
                    ]);
                }
            } elseif ($desiredStock < $currentStock) {
                $unitsToDeactivate = $product->productUnits()->where('is_active', true)->take($currentStock - $desiredStock)->get();
                foreach ($unitsToDeactivate as $unit) {
                    $unit->update(['is_active' => false]);
                    // Update cache
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $unit->qr_code,
                        'is_active' => false,
                    ]);
                }
            }

            // Update product cache
            Cache::forever($this->getProductCacheKey($product->id), [
                'id' => $product->id,
                'name' => $product->name,
                'size' => $product->size,
                'color' => $product->color,
                'purchase_price' => $product->purchase_price,
                'selling_price' => $product->selling_price,
                'discount_price' => $product->discount_price,
                'stock' => $desiredStock,
            ]);

            Log::info('Product updated: ID ' . $product->id . ' with ' . $desiredStock . ' units');

            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Produk dan unit berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Terjadi kesalahan, produk gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            Log::warning('Product not found for destroy: ID ' . $id);
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $product->productUnits()->delete();
            $product->delete();
            // Keep cache intact to allow QR codes to work
            Log::info('Product deleted: ID ' . $id);

            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Produk dan unit berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Terjadi kesalahan, produk gagal dihapus: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->input('search');

        if (empty($keyword)) {
            return response()->json(['products' => []], 200);
        }

        $products = Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('color', 'like', "%{$keyword}%")
            ->orWhere('size', 'like', "%{$keyword}%")
            ->select('id', 'name', 'size', 'color', 'selling_price', 'discount_price')
            ->paginate(10);

        Log::info('Search performed with keyword: ' . $keyword . ', found: ' . $products->count());

        return response()->json([
            'products' => $products->items()->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => $product->size,
                    'color' => $product->color,
                    'stock' => $product->product_units_count,
                    'selling_price' => $product->selling_price,
                    'discount_price' => $product->discount_price,
                ];
            }),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ]
        ], 200);
    }

    public function printQr($id)
    {
        $product = Product::find($id);

        if (!$product) {
            $cachedProduct = Cache::get($this->getProductCacheKey($id));
            if ($cachedProduct) {
                $product = (object) $cachedProduct;
                $product->productUnits = collect([]);
            } else {
                Log::warning('Product not found for print QR: ID ' . $id);
                return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
            }
        }

        $product->load(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }]);

        Log::info('Preparing to print QR codes for product ID ' . $id);

        return view('inventory.print_qr', compact('product'));
    }

    public function stockOpname()
    {
        Log::info('Stock opname page accessed');
        return view('inventory.stock_opname');
    }

    public function updatePhysicalStock(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            Log::warning('Product not found for stock opname: ID ' . $id);
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'physical_stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $currentStock = $product->productUnits()->where('is_active', true)->count();
            $desiredStock = $validated['physical_stock'];
            $difference = $desiredStock - $currentStock;
            $stockMessage = '';
            $stockMismatch = null;

            if ($difference > 0) {
                $stockMessage = "Produk terdapat selisih: lebih {$difference} unit";
                $stockMismatch = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'message' => "Stok tidak sesuai, lebih {$difference} unit",
                    'difference' => $difference
                ];
            } elseif ($difference < 0) {
                $stockMessage = "Produk terdapat selisih: kurang " . abs($difference) . " unit";
                $stockMismatch = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'message' => "Stok tidak sesuai, kurang " . abs($difference) . " unit",
                    'difference' => $difference
                ];
            } else {
                $stockMessage = "Stok sesuai dengan sistem";
            }

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

                    // Cache unit data
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $qrCode,
                        'is_active' => $unit->is_active,
                    ]);
                }
            } elseif ($desiredStock < $currentStock) {
                $unitsToDeactivate = $product->productUnits()->where('is_active', true)->take($currentStock - $desiredStock)->get();
                foreach ($unitsToDeactivate as $unit) {
                    $unit->update(['is_active' => false]);
                    // Update cache
                    Cache::forever($this->getUnitCacheKey($product->id, $unit->unit_code), [
                        'product_id' => $product->id,
                        'unit_code' => $unit->unit_code,
                        'qr_code' => $unit->qr_code,
                        'is_active' => false,
                    ]);
                }
            }

            // Update product cache
            Cache::forever($this->getProductCacheKey($product->id), [
                'id' => $product->id,
                'name' => $product->name,
                'size' => $product->size,
                'color' => $product->color,
                'purchase_price' => $product->purchase_price,
                'selling_price' => $product->selling_price,
                'discount_price' => $product->discount_price,
                'stock' => $desiredStock,
            ]);

            Log::info("Stock opname updated for product ID {$product->id}: Old stock {$currentStock}, New stock {$desiredStock}, Message: {$stockMessage}");

            if ($stockMismatch) {
                $existingMismatches = session('stock_mismatches', []);
                $existingMismatches[$product->id] = $stockMismatch;
                session(['stock_mismatches' => $existingMismatches]);
            } else {
                $existingMismatches = session('stock_mismatches', []);
                unset($existingMismatches[$product->id]);
                session(['stock_mismatches' => $existingMismatches]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stok fisik berhasil diperbarui',
                'stock_message' => $stockMessage,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $desiredStock,
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating physical stock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}