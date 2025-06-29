<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil produk dengan stok > 0, diurutkan berdasarkan nama dan ukuran
        $products = Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })
        ->having('product_units_count', '>', 0)
        ->orderBy('name')
        ->orderBy('size')
        ->paginate(10);

        // Statistik inventaris (total produk, stok menipis, total unit)
        // Catatan: Statistik ini mencakup semua produk, termasuk stok 0
        $totalProducts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })->count();

        $lowStockProducts = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })
        ->withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->having('product_units_count', '>=', 1)
        ->having('product_units_count', '<=', 10)
        ->count();

        $totalStock = ProductUnit::where('is_active', true)->count();

        // Mengambil session untuk badge "Baru" dan "Diperbarui"
        $newProducts = session('new_products', []);
        $updatedProducts = session('updated_products', []);

        return view('inventory.index', compact('products', 'totalProducts', 'lowStockProducts', 'totalStock', 'newProducts', 'updatedProducts'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('search');
        if (strlen($keyword) < 2) {
            return response()->json(['error' => true, 'message' => 'Kata kunci minimal 2 karakter.']);
        }

        // Pencarian produk dengan stok > 0
        $products = Product::where('name', 'like', "%{$keyword}%")
            ->orWhere('size', 'like', "%{$keyword}%")
            ->orWhere('color', 'like', "%{$keyword}%")
            ->withCount(['productUnits' => function ($query) {
                $query->where('is_active', true);
            }])
            ->whereHas('productUnits', function ($query) {
                $query->where('is_active', true);
            })
            ->having('product_units_count', '>', 0)
            ->orderBy('name')
            ->orderBy('size')
            ->paginate(10);

        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $validated['name'],
                'size' => $validated['size'],
                'color' => $validated['color'],
                'selling_price' => $validated['selling_price'],
                'discount_price' => $validated['discount_price'],
            ]);

            // Tambahkan unit produk sesuai stok
            for ($i = 0; $i < $validated['stock']; $i++) {
                ProductUnit::create([
                    'product_id' => $product->id,
                    'is_active' => true,
                ]);
            }

            DB::commit();

            // Simpan ID produk baru ke session untuk badge "Baru"
            $newProducts = session('new_products', []);
            $newProducts[] = $product->id;
            session(['new_products' => $newProducts]);

            return redirect()->route('inventory.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->findOrFail($id);
        return view('inventory.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $currentStock = $product->productUnits()->where('is_active', true)->count();

            $product->update([
                'name' => $validated['name'],
                'size' => $validated['size'],
                'color' => $validated['color'],
                'selling_price' => $validated['selling_price'],
                'discount_price' => $validated['discount_price'],
            ]);

            // Sesuaikan stok
            if ($validated['stock'] > $currentStock) {
                // Tambah unit baru
                for ($i = $currentStock; $i < $validated['stock']; $i++) {
                    ProductUnit::create([
                        'product_id' => $product->id,
                        'is_active' => true,
                    ]);
                }
            } elseif ($validated['stock'] < $currentStock) {
                // Nonaktifkan unit berlebih
                $unitsToDeactivate = $currentStock - $validated['stock'];
                $product->productUnits()->where('is_active', true)
                    ->take($unitsToDeactivate)
                    ->update(['is_active' => false]);
            }

            DB::commit();

            // Simpan ID produk yang diperbarui ke session untuk badge "Diperbarui"
            $updatedProducts = session('updated_products', []);
            $updatedProducts[] = $product->id;
            session(['updated_products' => $updatedProducts]);

            return redirect()->route('inventory.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->productUnits()->update(['is_active' => false]);
            $product->delete();
            DB::commit();
            return redirect()->route('inventory.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $product = Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->findOrFail($id);
        return view('inventory.show', compact('product'));
    }

    public function print_qr($id)
    {
        $product = Product::findOrFail($id);
        $qrCode = QrCode::size(200)->generate(route('inventory.show', $product->id));
        return view('inventory.print_qr', compact('product', 'qrCode'));
    }

    public function stock_opname(Request $request)
    {
        $products = Product::withCount(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])
        ->whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })
        ->having('product_units_count', '>', 0)
        ->get();

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'physical_stock' => 'required|array',
                'physical_stock.*' => 'required|integer|min:0',
            ]);

            $stockMismatches = [];
            foreach ($validated['physical_stock'] as $productId => $physicalStock) {
                $product = Product::withCount(['productUnits' => function ($query) {
                    $query->where('is_active', true);
                }])->findOrFail($productId);
                $systemStock = $product->product_units_count;

                if ($physicalStock != $systemStock) {
                    $difference = $physicalStock - $systemStock;
                    $stockMismatches[$productId] = [
                        'message' => "Ketidaksesuaian stok: Fisik ($physicalStock) vs Sistem ($systemStock)",
                        'difference' => $difference,
                        'physical_stock' => $physicalStock,
                    ];
                }
            }

            if (!empty($stockMismatches)) {
                session(['stock_mismatches' => $stockMismatches]);
                return redirect()->route('inventory.index')->with('error', 'Terdapat ketidaksesuaian stok.');
            }

            return redirect()->route('inventory.index')->with('success', 'Stock opname selesai, tidak ada ketidaksesuaian.');
        }

        return view('inventory.stock_opname', compact('products'));
    }
}
?>