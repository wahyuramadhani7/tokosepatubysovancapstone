<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Dashboard untuk Owner
    public function owner()
    {
        $today = Carbon::today('Asia/Jakarta');
        $currentMonth = Carbon::now('Asia/Jakarta')->startOfMonth();
        $endOfMonth = Carbon::now('Asia/Jakarta')->endOfMonth();

        // Ringkasan transaksi harian
        $recentTransactions = Transaction::with([
            'user' => function ($query) {
                $query->select('id', 'name');
            },
            'items.product' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalSales = Transaction::whereDate('created_at', $today)->sum('final_amount');
        
        // Hitung total unit aktif (bukan total produk)
        $totalUnits = ProductUnit::where('is_active', true)->count();

        // Produk terlaris berdasarkan transaksi bulan ini
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
            });

        // Transaksi harian per jam
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

        // Format data untuk grafik (jam 00:00 sampai 23:00)
        $hourlyData = [];
        $labels = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = sprintf('%02d:00', $hour);
            $hourlyData[] = $hourlyTransactions->get($hour, 0);
        }

        return view('owner.dashboard', compact(
            'recentTransactions',
            'totalTransactions',
            'totalSales',
            'totalUnits', // Mengganti totalProducts dengan totalUnits
            'topProducts',
            'hourlyData',
            'labels'
        ));
    }

    // Dashboard untuk Employee
    public function employee()
    {
        $today = Carbon::today('Asia/Jakarta');
        $currentMonth = Carbon::now('Asia/Jakarta')->startOfMonth();
        $endOfMonth = Carbon::now('Asia/Jakarta')->endOfMonth();

        // Ringkasan transaksi harian
        $recentTransactions = Transaction::with([
            'user' => function ($query) {
                $query->select('id', 'name');
            },
            'items.product' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalSales = Transaction::whereDate('created_at', $today)->sum('final_amount');
        
        // Hitung total unit aktif (bukan total produk)
        $totalUnits = ProductUnit::where('is_active', true)->count();

        // Produk terlaris berdasarkan transaksi bulan ini
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
            });

        // Transaksi harian per jam
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

        // Format data untuk grafik (jam 00:00 sampai 23:00)
        $hourlyData = [];
        $labels = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = sprintf('%02d:00', $hour);
            $hourlyData[] = $hourlyTransactions->get($hour, 0);
        }

        return view('employee.dashboard', compact(
            'recentTransactions',
            'totalTransactions',
            'totalSales',
            'totalUnits', // Mengganti totalProducts dengan totalUnits
            'topProducts',
            'hourlyData',
            'labels'
        ));
    }

    // Manajemen akun employee (khusus Owner)
    public function employeeAccounts()
    {
        $employees = User::where('role', 'employee')->get();

        // Ambil semua sesi aktif dari storage sesi
        $activeSessions = [];
        if (config('session.driver') === 'database') {
            $activeSessions = DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime')))
                ->pluck('user_id')
                ->toArray();
        }

        // Tambahkan status aktif ke setiap employee
        $employees = $employees->map(function ($employee) use ($activeSessions) {
            $employee->is_active = in_array($employee->id, $activeSessions);
            return $employee;
        });

        return view('owner.employee-accounts', compact('employees'));
    }

    public function createEmployee()
    {
        return view('owner.create-employee');
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'employee',
        ]);

        return redirect()->route('owner.employee-accounts')->with('success', 'Employee created successfully.');
    }

    public function editEmployee(User $user)
    {
        return view('owner.edit-employee', compact('user'));
    }

    public function updateEmployee(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $newPassword = null;
        if (!empty($validated['password'])) {
            $newPassword = $validated['password'];
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $message = 'Employee updated successfully.';
        if ($newPassword) {
            $message .= ' New password: ' . $newPassword . ' (Please share this securely with the employee and ensure they change it after login.)';
        }

        return redirect()->route('owner.employee-accounts')->with('success', $message);
    }

    public function deleteEmployee(User $user)
    {
        $user->delete();

        return redirect()->route('owner.employee-accounts')->with('success', 'Employee deleted successfully.');
    }

    // Method tambahan untuk optimasi performance (opsional)
    private function getTopProductsOptimized($currentMonth, $endOfMonth)
    {
        return DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$currentMonth, $endOfMonth])
            ->groupBy('transaction_items.product_id', 'products.name')
            ->select(
                'products.name',
                DB::raw('SUM(transaction_items.quantity) as quantity')
            )
            ->orderByDesc('quantity')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'quantity' => (int) $item->quantity
                ];
            });
    }
}