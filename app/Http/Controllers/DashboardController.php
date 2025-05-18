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
        // Ambil data untuk ringkasan transaksi harian
        $today = now()->format('Y-m-d');
        $recentTransactions = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get();
        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalSales = Transaction::whereDate('created_at', $today)->sum('final_amount');
        $totalProducts = Product::count();

        // Ambil data untuk produk terlaris (berdasarkan jumlah terjual)
        $topProducts = TransactionItem::select('product_id')
            ->with(['product' => function ($query) {
                $query->select('id', 'name');
            }])
            ->groupBy('product_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product ? $item->product->name : 'Unknown',
                    'quantity' => TransactionItem::where('product_id', $item->product_id)->sum('quantity'),
                ];
            });

        // Ambil data untuk transaksi harian (per jam untuk hari ini)
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

        // Format data untuk grafik (pastikan semua jam dari 00:00 sampai 23:00 ada)
        $hourlyData = [];
        $labels = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = sprintf('%02d:00', $hour); // Format: 00:00, 01:00, dst.
            $hourlyData[] = $hourlyTransactions->get($hour, 0); // Jumlah transaksi, default 0 jika tidak ada
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
        // Ambil data untuk ringkasan transaksi harian
        $today = now()->format('Y-m-d');
        $recentTransactions = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get();
        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalSales = Transaction::whereDate('created_at', $today)->sum('final_amount');
        $totalProducts = Product::count();

        // Ambil data untuk produk terlaris (berdasarkan jumlah terjual)
        $topProducts = TransactionItem::select('product_id')
            ->with(['product' => function ($query) {
                $query->select('id', 'name');
            }])
            ->groupBy('product_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product ? $item->product->name : 'Unknown',
                    'quantity' => TransactionItem::where('product_id', $item->product_id)->sum('quantity'),
                ];
            });

        // Ambil data untuk transaksi harian (per jam untuk hari ini)
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

        // Format data untuk grafik (pastikan semua jam dari 00:00 sampai 23:00 ada)
        $hourlyData = [];
        $labels = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = sprintf('%02d:00', $hour); // Format: 00:00, 01:00, dst.
            $hourlyData[] = $hourlyTransactions->get($hour, 0); // Jumlah transaksi, default 0 jika tidak ada
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