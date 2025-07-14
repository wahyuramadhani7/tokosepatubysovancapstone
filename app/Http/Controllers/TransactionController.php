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
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transactions.index');
    }

    public function fetch(Request $request)
    {
        try {
            $request->validate([
                'date' => 'nullable|date_format:Y-m-d',
                'payment_method' => 'nullable|in:cash,qris,debit,transfer,debit_mandiri,debit_bri,debit_bca',
                'status' => 'nullable|in:paid,pending,cancelled',
            ]);

            $date = $request->input('date', Carbon::today('Asia/Jakarta')->format('Y-m-d'));
            $paymentMethod = $request->input('payment_method');
            $status = $request->input('status');

            $query = Transaction::with(['items.product', 'items.productUnit', 'user'])
                ->whereDate('created_at', $date);

            if ($paymentMethod) {
                if (in_array($paymentMethod, ['debit_mandiri', 'debit_bri', 'debit_bca'])) {
                    $query->where('payment_method', $paymentMethod);
                } else {
                    $query->where('payment_method', $paymentMethod);
                }
            }

            if ($status) {
                $query->where('payment_status', $status);
            }

            $transactions = $query->latest()->get();

            return response()->json([
                'success' => true,
                'transactions' => $transactions->map(function ($transaction) {
                    $paymentMethod = $transaction->payment_method;
                    $cardType = null;
                    if (strpos($paymentMethod, 'debit_') === 0) {
                        $cardType = ucfirst(str_replace('debit_', '', $paymentMethod));
                        $paymentMethod = 'debit';
                    } elseif ($paymentMethod === 'qris') {
                        $paymentMethod = 'QRIS';
                    }

                    return [
                        'id' => $transaction->id,
                        'invoice_number' => $transaction->invoice_number,
                        'created_at' => $transaction->created_at,
                        'customer_name' => $transaction->customer_name,
                        'customer_phone' => $transaction->customer_phone,
                        'payment_method' => $paymentMethod,
                        'card_type' => $cardType,
                        'payment_status' => $transaction->payment_status,
                        'total_amount' => $transaction->total_amount,
                        'discount_amount' => $transaction->discount_amount,
                        'final_amount' => $transaction->final_amount,
                        'items' => $transaction->items->map(function ($item) {
                            return [
                                'product_id' => $item->product_id,
                                'product' => $item->product ? [
                                    'name' => $item->product->name,
                                    'size' => $item->product->size ?? '-',
                                    'color' => $item->product->color ?? '-',
                                ] : null,
                                'product_unit_id' => $item->product_unit_id,
                                'unit_code' => $item->productUnit ? $item->productUnit->unit_code : null,
                                'quantity' => $item->quantity,
                                'price' => $item->price,
                                'subtotal' => $item->subtotal,
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
                'total' => $transactions->count(),
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Input filter tidak valid: ' . implode(', ', $e->errors()['date'] ?? []),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Fetch transactions failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        $availableUnits = DB::table('products')
            ->join('product_units', 'products.id', '=', 'product_units.product_id')
            ->where('product_units.is_active', true)
            ->select([
                'products.id as product_id',
                'products.name as product_name',
                'products.color',
                'products.size',
                'products.selling_price',
                'products.discount_price',
                'product_units.unit_code',
            ])
            ->get()
            ->map(function ($unit) {
                return [
                    'product_id' => $unit->product_id,
                    'product_name' => $unit->product_name,
                    'color' => $unit->color ?? '-',
                    'size' => $unit->size ?? '-',
                    'selling_price' => $unit->selling_price,
                    'discount_price' => $unit->discount_price,
                    'unit_code' => $unit->unit_code,
                ];
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
                'payment_method' => 'required|string|in:cash,qris,debit,transfer',
                'card_type' => 'required_if:payment_method,debit|in:Mandiri,BRI,BCA|nullable',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.unit_code' => 'required|exists:product_units,unit_code,is_active,1',
                'products.*.discount_price' => 'nullable|numeric|min:0',
                'discount_amount' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $transaction = new Transaction();
            $transaction->invoice_number = Transaction::generateInvoiceNumber();
            $transaction->user_id = Auth::id();
            $transaction->payment_method = $request->payment_method === 'debit' 
                ? 'debit_' . strtolower($request->card_type) 
                : $request->payment_method;
            $transaction->customer_name = $request->customer_name;
            $transaction->customer_phone = $request->customer_phone;
            $transaction->customer_email = $request->customer_email;
            $transaction->notes = $request->notes;
            $transaction->payment_status = 'paid';
            $transaction->created_at = Carbon::now('Asia/Jakarta');
            $transaction->updated_at = Carbon::now('Asia/Jakarta');

            $totalAmount = 0;
            $transactionItems = [];

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $unit = ProductUnit::where('product_id', $item['product_id'])
                    ->where('unit_code', $item['unit_code'])
                    ->where('is_active', true)
                    ->firstOrFail();

                $price = isset($item['discount_price']) && $item['discount_price'] !== null 
                    ? $item['discount_price'] 
                    : $product->selling_price;
                $subtotal = $price;
                $totalAmount += $subtotal;

                $unit->update(['is_active' => false]);

                $transactionItems[] = new TransactionItem([
                    'product_id' => $product->id,
                    'product_unit_id' => $unit->id,
                    'quantity' => 1,
                    'price' => $price,
                    'discount' => 0,
                    'subtotal' => $subtotal,
                ]);
            }

            $newTotal = (float)$request->discount_amount;
            if ($newTotal > $totalAmount) {
                throw new \Exception('Harga akhir tidak boleh melebihi subtotal.');
            }
            $discountAmount = $totalAmount - $newTotal;
            $finalAmount = $newTotal;

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
        $date = $request->input('date', Carbon::today('Asia/Jakarta')->format('Y-m-d'));
        $month = $request->input('month', Carbon::today('Asia/Jakarta')->format('m'));
        $year = $request->input('year', Carbon::today('Asia/Jakarta')->format('Y'));
        $reportType = $request->input('report_type', 'daily');
        $productSearch = $request->input('product_search');

        $query = Transaction::with(['user', 'items.product', 'items.productUnit']);

        if ($reportType === 'monthly') {
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        } else {
            $query->whereDate('created_at', $date);
        }

        if ($request->user_id && (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan nama produk jika ada input pencarian
        if ($productSearch) {
            $query->whereHas('items.product', function ($q) use ($productSearch) {
                $q->where('name', 'like', '%' . $productSearch . '%');
            });
        }

        $transactions = $query->latest()->get();
        $totalSales = $transactions->sum('final_amount');
        $totalTransactions = $transactions->count();
        $totalDiscount = $transactions->sum('discount_amount');
        $totalProductsSold = $transactions->sum(function ($transaction) {
            return $transaction->items->sum('quantity');
        });

        return view('transactions.report', compact(
            'transactions',
            'totalSales',
            'totalTransactions',
            'totalDiscount',
            'totalProductsSold',
            'date',
            'month',
            'year',
            'reportType',
            'productSearch' // Kirim variabel productSearch ke view untuk mempertahankan input
        ));
    }

    public function addProductByQr($unitCode)
    {
        try {
            $unit = ProductUnit::where('unit_code', $unitCode)
                ->where('is_active', true)
                ->first();
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit produk tidak ditemukan atau sudah tidak aktif.'
                ], 404);
            }

            $product = $unit->product;
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'unit' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'color' => $product->color ?? '-',
                    'size' => $product->size ?? '-',
                    'selling_price' => $product->selling_price,
                    'discount_price' => $product->discount_price,
                    'unit_code' => $unit->unit_code,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('QR scan failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat unit produk: ' . $e->getMessage()
            ], 500);
        }
    }
}