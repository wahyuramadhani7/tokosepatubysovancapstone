<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function owner()
    {
        return view('owner.dashboard');
    }
    public function employee()
    {
        return view('employee.dashboard'); // Pastikan view 'employee/dashboard.blade.php' ada
    }

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
