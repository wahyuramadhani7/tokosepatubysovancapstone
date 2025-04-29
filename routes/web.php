<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login'); // Arahkan langsung ke halaman login
});

// Route untuk dashboard employee
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
});

// Route untuk dashboard owner
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');
    Route::get('/owner/employee-accounts', [DashboardController::class, 'employeeAccounts'])->name('owner.employee-accounts');

    // Tambahan untuk CRUD employee
    Route::get('/owner/employee-accounts/create', [DashboardController::class, 'createEmployee'])->name('owner.employee-accounts.create');
    Route::post('/owner/employee-accounts', [DashboardController::class, 'storeEmployee'])->name('owner.employee-accounts.store');
    Route::get('/owner/employee-accounts/{user}/edit', [DashboardController::class, 'editEmployee'])->name('owner.employee-accounts.edit');
    Route::put('/owner/employee-accounts/{user}', [DashboardController::class, 'updateEmployee'])->name('owner.employee-accounts.update');
    Route::delete('/owner/employee-accounts/{user}', [DashboardController::class, 'deleteEmployee'])->name('owner.employee-accounts.destroy');
});


// Route untuk inventory (diakses oleh employee dan owner)
Route::middleware(['auth'])->group(function () {
    Route::prefix('inventory')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/create', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/{product}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
        Route::put('/{product}', [InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('/{product}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::get('/history', [InventoryController::class, 'history'])->name('inventory.history');
        Route::get('/search', [InventoryController::class, 'search'])->name('inventory.search');
    });
});

// Route untuk dashboard utama setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route untuk profile dengan middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';