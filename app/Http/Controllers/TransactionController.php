<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['items.product', 'items.productUnit', 'user'])->latest()->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        // Fetch products with active product units
        $products = Product::whereHas('productUnits', function ($query) {
            $query->where('is_active', true);
        })->with(['productUnits' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        // Prepare available units data for the view
        $availableUnits = $products->flatMap(function ($product) {
            return $product->productUnits->map(function ($unit) use ($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'color' => $product->color,
                    'size' => $product->size,
                    'selling_price' => $product->selling_price,
                    'discount_price' => $product->discount_price,
                    'unit_code' => $unit->unit_code,
                ];
            });
        })->toArray();

        return view('transactions.create', compact('availableUnits'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'customer_name' => 'nullable|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_email' => 'nullable|email|max:255',
                'payment_method' => 'required|string|in:cash,credit_card,transfer',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.unit_code' => 'required|exists:product_units,unit_code,is_active,1',
                'products.*.discount_price' => 'nullable|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $transaction = new Transaction();
            $transaction->invoice_number = Transaction::generateInvoiceNumber();
            $transaction->user_id = Auth::id();
            $transaction->payment_method = $request->payment_method;
            $transaction->customer_name = $request->customer_name;
            $transaction->customer_phone = $request->customer_phone;
            $transaction->customer_email = $request->customer_email;
            $transaction->notes = $request->notes;
            $transaction->payment_status = 'paid';

            $totalAmount = 0;
            $transactionItems = [];

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $unit = ProductUnit::where('product_id', $item['product_id'])
                    ->where('unit_code', $item['unit_code'])
                    ->where('is_active', true)
                    ->firstOrFail();

                // Use discount_price if provided and not null, otherwise use selling_price
                $price = isset($item['discount_price']) && $item['discount_price'] !== null 
                    ? $item['discount_price'] 
                    : $product->selling_price;
                $subtotal = $price;
                $totalAmount += $subtotal;

                // Deactivate the product unit
                $unit->update(['is_active' => false]);

                $transactionItems[] = new TransactionItem([
                    'product_id' => $product->id,
                    'product_unit_id' => $unit->id,
                    'quantity' => 1,
                    'price' => $price,
                    'discount' => 0, // Per-item discount is not used here
                    'subtotal' => $subtotal,
                ]);
            }

            $discountAmount = (float)($request->discount_amount ?? 0);
            $finalAmount = max(0, $totalAmount - $discountAmount);

            $transaction->total_amount = $totalAmount;
            $transaction->discount_amount = $discountAmount;
            $transaction->final_amount = $finalAmount;
            $transaction->save();

            foreach ($transactionItems as $item) {
                $transaction->items()->save($item);
            }

            DB::commit();

            return redirect()->route('transactions.index')
                ->with('success', 'Transaksi berhasil diselesaikan.')
                ->with('transaction_id', $transaction->id)
                ->with('new_transaction', $transaction->load(['items.product', 'items.productUnit', 'user']));
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction store failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Transaksi gagal: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Transaction $transaction)
    {
        try {
            DB::beginTransaction();
            foreach ($transaction->items as $item) {
                if ($item->productUnit) {
                    $item->productUnit->update(['is_active' => true]);
                }
            }
            $transaction->items()->delete();
            $transaction->delete();
            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction delete failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'items.productUnit', 'user']);
        return view('transactions.show', compact('transaction'));
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['items.product', 'items.productUnit', 'user']);
        return view('transactions.print', compact('transaction'));
    }

    public function report(Request $request)
    {
        $dateStart = $request->date_start ?? now()->startOfMonth()->format('Y-m-d');
        $dateEnd = $request->date_end ?? now()->format('Y-m-d');

        $query = Transaction::with(['user', 'items.productUnit'])
            ->whereBetween('created_at', [$dateStart . ' 00:00:00', $dateEnd . ' 23:59:59']);

        if (Auth::user()->role === 'employee') {
            $query->where('user_id', Auth::id());
        } elseif ($request->user_id && (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')) {
            $query->where('user_id', $request->user_id);
        }

        $transactions = $query->latest()->get();
        $totalSales = $transactions->sum('final_amount');
        $totalTransactions = $transactions->count();

        return view('transactions.report', compact('transactions', 'totalSales', 'totalTransactions', 'dateStart', 'dateEnd'));
    }

    public function addProductByQr($unitCode)
    {
        try {
            $unit = ProductUnit::where('unit_code', $unitCode)->where('is_active', true)->first();
            if (!$unit) {
                return response()->json(['success' => false, 'message' => 'Unit produk tidak ditemukan atau sudah tidak aktif.'], 404);
            }

            $product = $unit->product;
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
            }

            return response()->json([
                'success' => true,
                'unit' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'color' => $product->color,
                    'size' => $product->size,
                    'selling_price' => $product->selling_price,
                    'discount_price' => $product->discount_price,
                    'unit_code' => $unit->unit_code,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('QR scan failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat unit produk: ' . $e->getMessage()], 500);
        }
    }
}