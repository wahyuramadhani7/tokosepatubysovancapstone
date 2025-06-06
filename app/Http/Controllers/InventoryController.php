<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

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
    * Generate and save the QR code for a product.
    *
    * @param string $content
    * @return string|Illuminate\View\View $qrCodePath
    */
   public function generateQrCode($content = null)
   {
       // Jika dipanggil tanpa parameter, gunakan URL default
       if ($content === null) {
           $content = 'https://example.com';
           $qrCode = QrCode::size(300)->generate($content);
           return view('inventory.qr_code', compact('qrCode'));
       }
       
       // Generate QR code sebagai SVG
       $filename = 'qrcode-' . time() . '.svg';
       $directory = 'qrcodes';
       $path = storage_path('app/public/' . $directory);
       
       // Pastikan direktori ada
       if (!file_exists($path)) {
           mkdir($path, 0755, true);
       }
       
       // Generate QR code sebagai SVG
       $svg = QrCode::size(300)
               ->format('svg')
               ->errorCorrection('H')
               ->generate($content);
       
       // Simpan file SVG secara manual
       file_put_contents($path . '/' . $filename, $svg);
       
       // Kembalikan path relatif untuk disimpan di database
       return $directory . '/' . $filename;
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
           // Create the product first without QR code
           $product = Product::create([
               'name' => $validated['brand'] . ' ' . $validated['model'],
               'size' => $validated['size'],
               'stock' => $validated['stock'],
               'purchase_price' => 0,
               'selling_price' => $validated['selling_price'],
               'color' => $validated['color'],
           ]);

           // Generate QR code with URL to product detail
           $qrCodeContent = route('inventory.show', $product->id);
           $qrCodePath = $this->generateQrCode($qrCodeContent);

           // Update product with QR code path
           $product->update(['qr_code' => $qrCodePath]);

           DB::commit();

           return redirect()->route('inventory.index')->with('success', 'Produk berhasil ditambahkan');
       } catch (\Exception $e) {
           DB::rollBack();
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
       return view('inventory.show', compact('product'));
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
       // Delete the QR code file if necessary
       if (Storage::exists('public/' . $product->qr_code)) {
           Storage::delete('public/' . $product->qr_code);
       }

       $product->delete();

       return redirect()->route('inventory.index')->with('success', 'Produk berhasil dihapus');
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

   /**
    * Display the QR code print page for a product.
    *
    * @param  \App\Models\Product  $product
    * @return \Illuminate\View\View
    */
   public function printQr(Product $product)
   {
       return view('inventory.print_qr', compact('product'));
   }
}