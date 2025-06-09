<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Dashboard untuk Owner
    public function owner()
    {
        $today = now()->format('Y-m-d');

        // Ringkasan transaksi harian
        $recentTransactions = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get();
        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalSales = Transaction::whereDate('created_at', $today)->sum('final_amount');
        $totalProducts = Product::count();

        // Produk terlaris berdasarkan transaksi hari ini
        $topProducts = TransactionItem::select('product_id')
            ->whereHas('transaction', function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            })
            ->with(['product' => function ($query) {
                $query->select('id', 'name');
            }])
            ->groupBy('product_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->take(5)
            ->get()
            ->map(function ($item) use ($today) {
                return [
                    'name' => $item->product ? $item->product->name : 'Produk Tidak Dikenal',
                    'quantity' => TransactionItem::where('product_id', $item->product_id)
                        ->whereHas('transaction', function ($query) use ($today) {
                            $query->whereDate('created_at', $today);
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
            'totalProducts',
            'topProducts',
            'hourlyData',
            'labels'
        ));
    }

    // Dashboard untuk Employee
    public function employee()
    {
        $today = now()->format('Y-m-d');

        // Ringkasan transaksi harian
        $recentTransactions = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get();
        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalSales = Transaction::whereDate('created_at', $today)->sum('final_amount');
        $totalProducts = Product::count();

        // Produk terlaris berdasarkan transaksi hari ini
        $topProducts = TransactionItem::select('product_id')
            ->whereHas('transaction', function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            })
            ->with(['product' => function ($query) {
                $query->select('id', 'name');
            }])
            ->groupBy('product_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->take(5)
            ->get()
            ->map(function ($item) use ($today) {
                return [
                    'name' => $item->product ? $item->product->name : 'Produk Tidak Dikenal',
                    'quantity' => TransactionItem::where('product_id', $item->product_id)
                        ->whereHas('transaction', function ($query) use ($today) {
                            $query->whereDate('created_at', $today);
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
            'totalProducts',
            'topProducts',
            'hourlyData',
            'labels'
        ));
    }

    // Manajemen akun employee (khusus Owner)
    public function employeeAccounts()
    {
        $employees = User::where('role', 'employee')->get();
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
        ]);

        $user->update($validated);

        return redirect()->route('owner.employee-accounts')->with('success', 'Employee updated successfully.');
    }

    public function deleteEmployee(User $user)
    {
        $user->delete();

        return redirect()->route('owner.employee-accounts')->with('success', 'Employee deleted successfully.');
    }
}