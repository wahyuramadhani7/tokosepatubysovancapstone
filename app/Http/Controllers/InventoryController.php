<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        // Generate a temporary unique identifier for the QR Code (we'll update it with the product ID later)
        $tempQrCodeContent = $validated['brand'] . '-' . $validated['model'] . '-' . $validated['size'] . '-' . time();
        $qrCode = QrCode::format('svg')->size(200)->generate($tempQrCodeContent);
        $qrCodeString = base64_encode($qrCode);

        // Create the product with the QR Code
        $product = Product::create([
            'qr_code' => $qrCodeString,
            'name' => $validated['brand'] . ' ' . $validated['model'],
            'size' => $validated['size'],
            'stock' => $validated['stock'],
            'purchase_price' => 0, // Set default, as it's not in the form
            'selling_price' => $validated['selling_price'],
            'color' => $validated['color'],
        ]);

        // Optionally, update the QR Code to include the product ID for better uniqueness
        $finalQrCodeContent = $product->id . '-' . $validated['brand'] . '-' . $validated['model'];
        $finalQrCode = QrCode::format('svg')->size(200)->generate($finalQrCodeContent);
        $finalQrCodeString = base64_encode($finalQrCode);
        $product->update(['qr_code' => $finalQrCodeString]);

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
            'qr_code' => 'required|string|max:255|unique:products,qr_code,' . $product->id,
            'name' => 'required|string|max:255',
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
            ->orWhere('qr_code', 'like', "%{$keyword}%")
            ->paginate(10);
        
        // Get inventory summary data
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 10)->count();
        $totalStock = Product::sum('stock');
        
        return view('inventory.index', compact('products', 'totalProducts', 'lowStockProducts', 'totalStock'));
    }
}