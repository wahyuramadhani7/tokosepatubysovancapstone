<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        $keys = Cache::getRedis()->keys('transaction_search_*');
        foreach ($keys as $key) {
            Cache::forget(str_replace(Cache::getPrefix(), '', $key));
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
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch transactions: ' . $e->getMessage(),
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
                    return [
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
                                'color' => $item->productUnit?->color,
                                'size' => $item->productUnit?->size,
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
        $validator = Validator::make($request->all(), [
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'payment_method' => 'required|in:cash,qris,debit,transfer',
            'card_type' => 'required_if:payment_method,debit|in:Mandiri,BRI,BCA|nullable',
            'discount_amount' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.unit_code' => 'required|exists:product_units,unit_code',
            'products.*.discount_price' => 'nullable|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $items = [];

                foreach ($request->products as $product) {
                    $unit = ProductUnit::where('unit_code', $product['unit_code'])
                        ->where('is_active', true)
                        ->firstOrFail();
                    $price = $product['discount_price'] ?? $unit->selling_price;
                    $subtotal = $price * $product['quantity'];
                    $totalAmount += $subtotal;

                    $items[] = [
                        'product_id' => $product['product_id'],
                        'product_unit_id' => $unit->id,
                        'quantity' => $product['quantity'],
                        'price' => $price,
                        'discount' => $unit->selling_price - $price,
                        'subtotal' => $subtotal,
                    ];
                }

                $discountAmount = (float) $request->discount_amount;
                $finalAmount = max(0, $totalAmount - $discountAmount);

                $transaction = Transaction::create([
                    'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                    'user_id' => Auth::id(),
                    'total_amount' => $totalAmount,
                    'tax_amount' => 0,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                    'payment_method' => $request->payment_method,
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

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction created successfully',
                    'data' => [
                        'transaction_id' => $transaction->id,
                        'invoice_number' => $transaction->invoice_number,
                    ],
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction: ' . $e->getMessage(),
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
                        'color' => $item->productUnit?->color,
                        'size' => $item->productUnit?->size,
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
                return response()->json([
                    'success' => false,
                    'message' => 'Unit produk tidak ditemukan',
                ], 404);
            }

            $product = $unit->product;

            return response()->json([
                'success' => true,
                'data' => [
                    'product_id' => $unit->product_id,
                    'product_name' => $product?->name,
                    'color' => $unit->color,
                    'size' => $unit->size,
                    'selling_price' => (float) $unit->selling_price,
                    'discount_price' => $unit->discount_price ? (float) $unit->discount_price : null,
                    'unit_code' => $unit->unit_code,
                ],
            ], 200, ['Cache-Control' => 'no-cache']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat unit produk: ' . $e->getMessage(),
            ], 500);
        }
    }
}