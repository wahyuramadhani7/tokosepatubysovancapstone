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
        $transactions = Transaction::with('user')->latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        // Hanya ambil produk yang memiliki stok > 0
        $products = Product::where('stock', '>', 0)->get();
        return view('transactions.create', compact('products'));
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
            $finalAmount = 0;
            $taxRate = 0.11; // 11% tax rate
            $transactionItems = [];
            
            // Validasi ketersediaan stok produk
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stock < $item['quantity']) {
                    return back()->withErrors(['stock' => "Stok tidak cukup untuk {$product->name}. Tersedia: {$product->stock}"]);
                }
            }

            // Buat transaksi baru
            $transaction = new Transaction();
            $transaction->invoice_number = Transaction::generateInvoiceNumber();
            $transaction->user_id = Auth::id();
            $transaction->payment_method = $request->payment_method;
            $transaction->customer_name = $request->customer_name;
            $transaction->customer_phone = $request->customer_phone;
            $transaction->customer_email = $request->customer_email;
            $transaction->notes = $request->notes;
            $transaction->payment_status = 'paid'; // Default ke status paid
            
            // Hitung total dan simpan item
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                $quantity = (int)$item['quantity'];
                $price = $product->selling_price; // Gunakan selling_price dari model Product
                $discount = isset($item['discount']) ? (float)$item['discount'] : 0;
                $subtotal = ($price * $quantity) - $discount;
                
                $totalAmount += $subtotal;
                
                // Kurangi stok produk
                $product->stock -= $quantity;
                $product->save();
                
                // Buat item transaksi
                $transactionItem = new TransactionItem([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => $discount,
                    'subtotal' => $subtotal,
                ]);
                
                // Simpan di array untuk diproses setelah transaksi dibuat
                $transactionItems[] = $transactionItem;
            }
            
            // Hitung pajak dan jumlah akhir
            $discountAmount = (float)($request->discount_amount ?? 0);
            $taxAmount = ($totalAmount - $discountAmount) * $taxRate;
            $finalAmount = $totalAmount - $discountAmount + $taxAmount;
            
            // Update nilai transaksi
            $transaction->total_amount = $totalAmount;
            $transaction->discount_amount = $discountAmount;
            $transaction->tax_amount = $taxAmount;
            $transaction->final_amount = $finalAmount;
            $transaction->save();
            
            // Simpan item transaksi
            foreach ($transactionItems as $item) {
                $transaction->items()->save($item);
            }
            
            DB::commit();
            
            // Redirect ke halaman index dengan ID transaksi baru untuk digunakan di notifikasi
            return redirect()->route('transactions.index')
                ->with('success', 'Transaksi berhasil diselesaikan.')
                ->with('transaction_id', $transaction->id);
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
        
        // Cek apakah request meminta format HTML saja (untuk preview)
        if (request('format') === 'html') {
            return view('transactions.print', compact('transaction'))->render();
        }
        
        return view('transactions.print', compact('transaction'));
    }

    public function report(Request $request)
    {
        $dateStart = $request->date_start ?? now()->startOfMonth()->format('Y-m-d');
        $dateEnd = $request->date_end ?? now()->format('Y-m-d');
        
        $query = Transaction::with('user')
            ->whereBetween('created_at', [$dateStart . ' 00:00:00', $dateEnd . ' 23:59:59']);
            
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        $transactions = $query->latest()->get();
        $totalSales = $transactions->sum('final_amount');
        $totalTransactions = $transactions->count();
        
        return view('transactions.report', compact('transactions', 'totalSales', 'totalTransactions', 'dateStart', 'dateEnd'));
    }
}