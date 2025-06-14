<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display the inventory management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with('stockVerifications')->paginate(10);
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 10)->count();
        $totalStock = Product::sum('stock');

        Log::info('Loaded products for inventory index: ' . $products->count());
        return view('inventory.index', compact('products', 'totalProducts', 'lowStockProducts', 'totalStock'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => trim($validated['brand'] . ' ' . $validated['model']),
                'size' => $validated['size'],
                'stock' => $validated['stock'],
                'purchase_price' => 0,
                'selling_price' => $validated['selling_price'],
                'color' => $validated['color'],
                'physical_stock' => $validated['stock'],
            ]);

            Log::info('Product created: ID ' . $product->id);

            DB::commit();

            return redirect()->route('inventory.index')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing product: ' . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Terjadi kesalahan, produk gagal ditambahkan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product details.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        if (!$product) {
            Log::warning('Product not found for show: ID ' . request()->segment(2));
            return response()->view('errors.404-public', [], 404);
        }

        return view('inventory.show', compact('product'));
    }

    /**
     * Return product data in JSON format (only for explicit JSON requests).
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(Product $product)
    {
        if (!$product) {
            Log::warning('Product not found for JSON: ID ' . request()->segment(2));
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        Log::debug('JSON response for product ID ' . $product->id . ': ', (array) $product);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'color' => $product->color,
            'size' => $product->size,
            'selling_price' => $product->selling_price,
            'stock' => $product->stock,
            'physical_stock' => $product->physical_stock ?? 0,
        ], 200);
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        if (!$product) {
            Log::warning('Product not found for edit: ID ' . request()->segment(2));
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }
        return view('inventory.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        if (!$product) {
            Log::warning('Product not found for update: ID ' . request()->segment(2));
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'color' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'name' => trim($validated['brand'] . ' ' . $validated['model']),
                'size' => $validated['size'],
                'stock' => $validated['stock'],
                'purchase_price' => $validated['purchase_price'],
                'selling_price' => $validated['selling_price'],
                'color' => $validated['color'],
                'physical_stock' => $product->physical_stock ?? $validated['stock'],
            ]);

            Log::info('Product updated: ID ' . $product->id);

            DB::commit();

            return redirect()->route('inventory.index')->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Terjadi kesalahan, produk gagal diperbarui: ' . $e->getMessage());
        }
    }

    /**
     * Update the physical stock of a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhysicalStock(Request $request, Product $product)
    {
        if (!$product) {
            Log::warning('Product not found for physical stock update: ID ' . request()->segment(2));
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'physical_stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Explicitly update physical_stock
            $updated = $product->update([
                'physical_stock' => $validated['physical_stock'],
            ]);

            if (!$updated) {
                throw new \Exception('Failed to update physical_stock in the database');
            }

            // Reload the model to ensure the updated value is reflected
            $product->refresh();

            // Verify the update
            Log::info('Physical stock updated for product ID ' . $product->id . ' to: ' . $product->physical_stock);

            // Create stock verification record
            StockVerification::create([
                'product_id' => $product->id,
                'system_stock' => $product->stock,
                'physical_stock' => $validated['physical_stock'],
                'discrepancy' => $product->stock - $validated['physical_stock'],
                'notes' => $request->input('notes', 'Stock verification via QR scan'),
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stok fisik berhasil diperbarui',
                'physical_stock' => $product->physical_stock,
                'product_id' => $product->id
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating physical stock for product ID ' . $product->id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui stok fisik: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        if (!$product) {
            Log::warning('Product not found for destroy: ID ' . request()->segment(2));
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }

        $product->delete();
        Log::info('Product deleted: ID ' . $product->id);

        return redirect()->route('inventory.index')->with('success', 'Produk berhasil dihapus');
    }

    /**
     * Search products by keyword.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $keyword = $request->input('search');

        if (empty($keyword)) {
            return response()->json(['products' => []], 200);
        }

        $products = Product::where('name', 'like', "%{$keyword}%")
            ->orWhere('color', 'like', "%{$keyword}%")
            ->orWhere('size', 'like', "%{$keyword}%")
            ->select('id', 'name', 'size', 'color', 'stock', 'selling_price', 'physical_stock')
            ->paginate(10);

        Log::info('Search performed with keyword: ' . $keyword . ', found: ' . $products->count());

        return response()->json([
            'products' => $products->items(),
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

    /**
     * Display the QR code print page for a product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function printQr(Product $product)
    {
        if (!$product) {
            Log::warning('Product not found for print QR: ID ' . request()->segment(2));
            return redirect()->route('inventory.index')->with('error', 'Produk tidak ditemukan.');
        }

        Log::info('Preparing to print QR codes for product ID ' . $product->id . ' with stock: ' . $product->stock);

        return view('inventory.print_qr', compact('product'));
    }
}