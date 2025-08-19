<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // Cache keys
    private function getTransactionCacheKey($id)
    {
        return "transaction_{$id}";
    }

    private function getSearchCacheKey($date, $paymentMethod, $status, $page, $perPage)
    {
        return 'transaction_search_' . md5($date . '_' . $paymentMethod . '_' . $status . '_' . $page . '_' . $perPage);
    }

    /**
     * Clear all search-related caches
     */
    private function clearSearchCaches()
    {
        try {
            if (config('cache.default') === 'database') {
                DB::table('cache')
                    ->where('key', 'like', 'transaction_search_%')
                    ->delete();
            } else {
                $keys = Cache::store('redis')->getRedis()->keys('transaction_search_*');
                foreach ($keys as $key) {
                    Cache::forget(str_replace(Cache::getPrefix(), '', $key));
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear search caches: ' . $e->getMessage());
        }
    }

    /**
     * Fetch transactions with filters and pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $date = $request->query('date', '');
        $paymentMethod = $request->query('payment_method', '');
        $status = $request->query('status', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 100);
        $noCache = $request->query('no_cache', false);
        $cacheKey = $this->getSearchCacheKey($date, $paymentMethod, $status, $page, $perPage);

        $transactions = $noCache ? null : Cache::get($cacheKey);

        if (!$transactions) {
            try {
                $query = Transaction::with(['items.product', 'items.productUnit', 'user'])
                    ->when($date, function ($query, $date) {
                        return $query->whereDate('created_at', $date);
                    })
                    ->when($paymentMethod, function ($query, $method) {
                        return $query->where('payment_method', $method);
                    })
                    ->when($status, function ($query, $status) {
                        return $query->where('payment_status', $status);
                    })
                    ->orderBy('id', 'desc');

                $transactions = $query->paginate($perPage)
                    ->appends([
                        'date' => $date,
                        'payment_method' => $paymentMethod,
                        'status' => $status,
                        'per_page' => $perPage,
                    ]);

                if (!$noCache) {
                    Cache::put($cacheKey, $transactions, now()->addMinutes(1));
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch transactions: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil transaksi: ' . $e->getMessage(),
                ], 500);
            }
        }

        $totalTransactions = Transaction::count();
        $totalAmount = Transaction::sum('final_amount');
        $pendingTransactions = Transaction::where('payment_status', 'pending')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'transactions' => collect($transactions->items())->map(function ($transaction) {
                    $paymentMethod = $transaction->payment_method;
                    $cardType = $transaction->card_type;
                    if (strpos($paymentMethod, 'debit_') === 0) {
                        $cardType = ucfirst(str_replace('debit_', '', $paymentMethod));
                        $paymentMethod = 'debit';
                    } elseif ($paymentMethod === 'qris') {
                        $paymentMethod = 'QRIS';
                    }

                    return [
                        'id' => $transaction->id,
                        'invoice_number' => $transaction->invoice_number,
                        'user_id' => $transaction->user_id,
                        'user_name' => $transaction->user?->name,
                        'total_amount' => (float) $transaction->total_amount,
                        'tax_amount' => (float) $transaction->tax_amount,
                        'discount_amount' => (float) $transaction->discount_amount,
                        'final_amount' => (float) $transaction->final_amount,
                        'payment_method' => $paymentMethod,
                        'card_type' => $cardType,
                        'payment_status' => $transaction->payment_status,
                        'customer_name' => $transaction->customer_name,
                        'customer_phone' => $transaction->customer_phone,
                        'customer_email' => $transaction->customer_email,
                        'notes' => $transaction->notes,
                        'created_at' => $transaction->created_at->toISOString(),
                        'items' => $transaction->items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'product_id' => $item->product_id,
                                'product_name' => $item->product?->name,
                                'product_unit_id' => $item->product_unit_id,
                                'unit_code' => $item->productUnit?->unit_code,
                                'color' => $item->product?->color ?? '-',
                                'size' => $item->product?->size ?? '-',
                                'quantity' => (int) $item->quantity,
                                'price' => (float) $item->price,
                                'discount' => (float) $item->discount,
                                'subtotal' => (float) $item->subtotal,
                            ];
                        })->toArray(),
                    ];
                }),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'total' => $transactions->total(),
                    'per_page' => $transactions->perPage(),
                ],
                'total_transactions' => $totalTransactions,
                'total_amount' => (float) $totalAmount,
                'pending_transactions' => $pendingTransactions,
            ]
        ], 200, ['Cache-Control' => 'no-cache']);
    }

    /**
     * Store a new transaction
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Menerima request transaksi', $request->all());

        $validator = Validator::make($request->all(), [
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'payment_method' => 'required|in:cash,qris,debit,transfer',
            'card_type' => 'required_if:payment_method,debit|in:Mandiri,BRI,BCA|nullable',
            'discount_amount' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.unit_code' => 'required|exists:product_units,unit_code,is_active,1',
            'products.*.discount_price' => 'nullable|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            Log::error('Validasi gagal: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_merge(...array_values($validator->errors()->toArray()))),
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $items = [];

                foreach ($request->products as $index => $product) {
                    // Validasi unit produk
                    $unit = ProductUnit::where('unit_code', $product['unit_code'])
                        ->where('is_active', true)
                        ->first();
                    if (!$unit) {
                        Log::error('Unit produk tidak ditemukan', [
                            'unit_code' => $product['unit_code'],
                            'index' => $index,
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => "Unit produk dengan kode {$product['unit_code']} tidak ditemukan atau tidak aktif.",
                        ], 404);
                    }

                    // Validasi produk berdasarkan product_id dari unit
                    $productModel = Product::where('id', $unit->product_id)
                        ->where('stock', '>=', $product['quantity'])
                        ->first();
                    if (!$productModel) {
                        Log::error('Produk tidak ditemukan atau stok tidak cukup', [
                            'product_id' => $unit->product_id,
                            'unit_code' => $product['unit_code'],
                            'quantity' => $product['quantity'],
                            'index' => $index,
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => "Produk untuk unit kode {$product['unit_code']} tidak ditemukan atau stok tidak cukup.",
                        ], 404);
                    }

                    $price = $product['discount_price'] ?? $productModel->selling_price;
                    $subtotal = $price * $product['quantity'];
                    $totalAmount += $subtotal;

                    $items[] = [
                        'product_id' => $unit->product_id,
                        'product_unit_id' => $unit->id,
                        'quantity' => $product['quantity'],
                        'price' => $price,
                        'discount' => $productModel->selling_price - $price,
                        'subtotal' => $subtotal,
                    ];

                    // Kurangi stok dan nonaktifkan unit
                    $productModel->decrement('stock', $product['quantity']);
                    $unit->update(['is_active' => false]);
                }

                $discountAmount = (float) $request->discount_amount;
                if ($discountAmount > $totalAmount) {
                    Log::error('Diskon melebihi total jumlah', [
                        'discount_amount' => $discountAmount,
                        'total_amount' => $totalAmount,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Harga akhir tidak boleh melebihi subtotal.',
                    ], 422);
                }
                $finalAmount = max(0, $totalAmount - $discountAmount);

                $transaction = Transaction::create([
                    'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                    'user_id' => Auth::id(),
                    'total_amount' => $totalAmount,
                    'tax_amount' => 0,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                    'payment_method' => $request->payment_method === 'debit' 
                        ? 'debit_' . strtolower($request->card_type) 
                        : $request->payment_method,
                    'card_type' => $request->card_type,
                    'payment_status' => 'paid',
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'customer_email' => $request->customer_email,
                    'notes' => $request->notes,
                ]);

                foreach ($items as $item) {
                    $transaction->items()->create($item);
                }

                // Cache the transaction
                Cache::forever($this->getTransactionCacheKey($transaction->id), [
                    'id' => $transaction->id,
                    'invoice_number' => $transaction->invoice_number,
                    'user_id' => $transaction->user_id,
                    'user_name' => $transaction->user?->name,
                    'total_amount' => (float) $transaction->total_amount,
                    'tax_amount' => (float) $transaction->tax_amount,
                    'discount_amount' => (float) $transaction->discount_amount,
                    'final_amount' => (float) $transaction->final_amount,
                    'payment_method' => $transaction->payment_method,
                    'card_type' => $transaction->card_type,
                    'payment_status' => $transaction->payment_status,
                    'customer_name' => $transaction->customer_name,
                    'customer_phone' => $transaction->customer_phone,
                    'customer_email' => $transaction->customer_email,
                    'notes' => $transaction->notes,
                    'created_at' => $transaction->created_at->toISOString(),
                ]);

                // Clear search caches
                $this->clearSearchCaches();

                Log::info('Transaksi berhasil dibuat', ['transaction_id' => $transaction->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil dibuat',
                    'data' => [
                        'transaction_id' => $transaction->id,
                        'invoice_number' => $transaction->invoice_number,
                    ],
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Gagal membuat transaksi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single transaction
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $transaction = Transaction::with(['items.product', 'items.productUnit', 'user'])->find($id);

        if (!$transaction) {
            $cachedTransaction = Cache::get($this->getTransactionCacheKey($id));
            if ($cachedTransaction) {
                $transaction = (object) $cachedTransaction;
                $transaction->items = collect([]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan',
                ], 404);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'user_id' => $transaction->user_id,
                'user_name' => $transaction->user?->name,
                'total_amount' => (float) $transaction->total_amount,
                'tax_amount' => (float) $transaction->tax_amount,
                'discount_amount' => (float) $transaction->discount_amount,
                'final_amount' => (float) $transaction->final_amount,
                'payment_method' => $transaction->payment_method,
                'card_type' => $transaction->card_type,
                'payment_status' => $transaction->payment_status,
                'customer_name' => $transaction->customer_name,
                'customer_phone' => $transaction->customer_phone,
                'customer_email' => $transaction->customer_email,
                'notes' => $transaction->notes,
                'created_at' => $transaction->created_at->toISOString(),
                'items' => $transaction->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product?->name,
                        'product_unit_id' => $item->product_unit_id,
                        'unit_code' => $item->productUnit?->unit_code,
                        'color' => $item->product?->color ?? '-',
                        'size' => $item->product?->size ?? '-',
                        'quantity' => (int) $item->quantity,
                        'price' => (float) $item->price,
                        'discount' => (float) $item->discount,
                        'subtotal' => (float) $item->subtotal,
                    ];
                })->toArray(),
            ]
        ], 200, ['Cache-Control' => 'no-cache']);
    }

    /**
     * Add product by unit code (for QR scanning)
     *
     * @param string $unitCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct($unitCode)
    {
        try {
            $unit = ProductUnit::where('unit_code', $unitCode)
                ->where('is_active', true)
                ->first();

            if (!$unit) {
                Log::error('Unit produk tidak ditemukan: ' . $unitCode);
                return response()->json([
                    'success' => false,
                    'message' => 'Unit produk tidak ditemukan atau sudah tidak aktif.',
                ], 404);
            }

            $product = Product::where('id', $unit->product_id)
                ->where('stock', '>', 0)
                ->first();

            if (!$product) {
                Log::error('Produk tidak ditemukan atau stok habis: ' . $unit->product_id);
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan atau stok habis.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'product_id' => $unit->product_id,
                    'product_name' => $product->name,
                    'color' => $product->color ?? '-',
                    'size' => $product->size ?? '-',
                    'selling_price' => (float) $product->selling_price,
                    'discount_price' => $product->discount_price ? (float) $product->discount_price : null,
                    'unit_code' => $unit->unit_code,
                ],
            ], 200, ['Cache-Control' => 'no-cache']);
        } catch (\Exception $e) {
            Log::error('Gagal memuat unit produk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat unit produk: ' . $e->getMessage(),
            ], 500);
        }
    }
}