<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['items.product', 'user'])->latest()->paginate(10); // Mengurangi perpage untuk konsistensi
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('transactions.create', compact('products'));
    }

    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            $transaction->items()->delete();
            $transaction->delete();
            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'payment_method' => 'required|string|in:cash,credit_card,transfer',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $transactionItems = [];

            // Validasi stok
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stock < $item['quantity']) {
                    return back()->withErrors(['stock' => "Stok tidak cukup untuk {$product->name}. Tersedia: {$product->stock}"]);
                }
            }

            $transaction = new Transaction();
            $transaction->invoice_number = Transaction::generateInvoiceNumber();
            $transaction->user_id = Auth::id();
            $transaction->payment_method = $request->payment_method;
            $transaction->customer_name = $request->customer_name;
            $transaction->customer_phone = $request->customer_phone;
            $transaction->customer_email = $request->customer_email;
            $transaction->notes = $request->notes;
            $transaction->payment_status = 'paid';

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                $quantity = (int)$item['quantity'];
                $price = $product->selling_price;
                $discount = isset($item['discount']) ? (float)$item['discount'] : 0;
                $subtotal = ($price * $quantity) - $discount;

                $totalAmount += $subtotal;

                $product->stock -= $quantity;
                $product->save();

                $transactionItem = new TransactionItem([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => $discount,
                    'subtotal' => $subtotal,
                ]);

                $transactionItems[] = $transactionItem;
            }

            $discountAmount = (float)($request->discount_amount ?? 0);
            $finalAmount = $totalAmount - $discountAmount;

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
                ->with('new_transaction', $transaction->load(['items.product', 'user']));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Transaksi gagal: ' . $e->getMessage()]);
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'user']);
        return view('transactions.show', compact('transaction'));
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['items.product', 'user']);
        return view('transactions.print', compact('transaction'));
    }

    public function report(Request $request)
    {
        $dateStart = $request->date_start ?? now()->startOfMonth()->format('Y-m-d');
        $dateEnd = $request->date_end ?? now()->format('Y-m-d');

        $query = Transaction::with('user')
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

    public function addProductByQr(Product $product)
    {
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        if ($product->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok produk habis.'], 400);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'color' => $product->color,
                'size' => $product->size,
                'selling_price' => $product->selling_price,
                'stock' => $product->stock,
            ],
        ], 200);
    }
}