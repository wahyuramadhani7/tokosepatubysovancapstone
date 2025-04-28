<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InventoryController extends Controller
{
    /**
     * Display the inventory management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all products
        $products = Product::paginate(10);
        
        // Get inventory summary data
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 10)->count();
        $totalStock = Product::sum('stock');
        
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
            'barcode' => 'required|string|max:255|unique:products',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('inventory.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
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
        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('inventory.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('inventory.index')->with('success', 'Produk berhasil dihapus');
    }

    /**
     * Display the inventory history.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        // This would typically come from a transaction or history model
        // For now, just redirect to index
        return redirect()->route('inventory.index')->with('info', 'Fitur riwayat masih dalam pengembangan');
    }

    /**
     * Search products by keyword.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $keyword = $request->input('search');
        
        $products = Product::where('name', 'like', "%{$keyword}%")
            ->orWhere('barcode', 'like', "%{$keyword}%")
            ->orWhere('category', 'like', "%{$keyword}%")
            ->paginate(10);
        
        // Get inventory summary data
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 10)->count();
        $totalStock = Product::sum('stock');
        
        return view('inventory.index', compact('products', 'totalProducts', 'lowStockProducts', 'totalStock'));
    }
}