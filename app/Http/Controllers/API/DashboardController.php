<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Token tidak valid atau tidak disediakan',
            ], 401);
        }

        try {
            $today = Carbon::today('Asia/Jakarta');
            $currentMonth = Carbon::now('Asia/Jakarta')->startOfMonth();
            $endOfMonth = Carbon::now('Asia/Jakarta')->endOfMonth();

            // Ringkasan transaksi harian
            $totalTransactions = Transaction::whereDate('created_at', $today)->count();
            $totalSales = Transaction::whereDate('created_at', $today)->sum('final_amount');

            // Jumlah produk aktif
            $totalProducts = Product::whereHas('productUnits', function ($query) {
                $query->where('is_active', true);
            })->count();

            // Produk terlaris bulan ini
            $topProducts = TransactionItem::select('product_id')
                ->whereHas('transaction', function ($query) use ($currentMonth, $endOfMonth) {
                    $query->whereBetween('created_at', [$currentMonth, $endOfMonth]);
                })
                ->with(['product' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->groupBy('product_id')
                ->orderByRaw('SUM(quantity) DESC')
                ->take(5)
                ->get()
                ->map(function ($item) use ($currentMonth, $endOfMonth) {
                    return [
                        'name' => $item->product ? $item->product->name : 'Produk Tidak Dikenal',
                        'quantity' => TransactionItem::where('product_id', $item->product_id)
                            ->whereHas('transaction', function ($query) use ($currentMonth, $endOfMonth) {
                                $query->whereBetween('created_at', [$currentMonth, $endOfMonth]);
                            })
                            ->sum('quantity'),
                    ];
                })->toArray();

            // Transaksi per jam untuk grafik
            $hourlyTransactions = Transaction::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
                ->whereDate('created_at', $today)
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->hour => $item->count];
                });

            $hourlyData = [];
            $labels = [];
            for ($hour = 0; $hour < 24; $hour++) {
                $labels[] = sprintf('%02d:00', $hour);
                $hourlyData[] = $hourlyTransactions->get($hour, 0);
            }

            // Detil semua transaksi hari ini
            $recentTransactions = Transaction::whereDate('created_at', $today)
                ->with(['user' => function ($query) {
                    $query->select('id', 'name');
                }, 'items.product' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'created_at' => $transaction->created_at->format('d-m-Y H:i'),
                        'user' => $transaction->user ? ['name' => $transaction->user->name] : ['name' => 'Unknown'],
                        'items' => $transaction->items->map(function ($item) {
                            return [
                                'product' => $item->product ? ['name' => $item->product->name] : ['name' => 'Produk Tidak Dikenal'],
                            ];
                        })->toArray(),
                        'final_amount' => $transaction->final_amount,
                        'status' => $transaction->status ?? 'Selesai',
                    ];
                })->toArray();

            Log::info('Recent Transactions:', ['count' => count($recentTransactions), 'data' => $recentTransactions]);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_transactions' => $totalTransactions,
                    'total_sales' => $totalSales,
                    'total_products' => $totalProducts,
                    'top_products' => $topProducts,
                    'hourly_data' => $hourlyData,
                    'labels' => $labels,
                    'recent_transactions' => $recentTransactions,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Fetch dashboard data failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dashboard: ' . $e->getMessage(),
            ], 500);
        }
    }
}