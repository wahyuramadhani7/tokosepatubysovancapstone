<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VisitorMonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Public routes for product details and QR code printing
Route::prefix('inventory')->group(function () {
    Route::get('/{product}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::get('/{product}/print-qr', [InventoryController::class, 'printQr'])->name('inventory.print_qr');
});

// Route untuk dashboard employee
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
});

// Route untuk dashboard owner
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');
    Route::get('/owner/employee-accounts', [DashboardController::class, 'employeeAccounts'])->name('owner.employee-accounts');
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
       Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/{product}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
        Route::put('/{product}', [InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('/{product}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::get('/history', [InventoryController::class, 'history'])->name('inventory.history');
        Route::get('/search', [InventoryController::class, 'search'])->name('inventory.search');
        Route::get('/{product}/json', [InventoryController::class, 'json'])->name('inventory.json');
    });

    // Transactions Routes - accessible to all authenticated users
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');

    // Transaction Reports - accessible to all authenticated users
    Route::get('/transactions/reports/sales', [TransactionController::class, 'report'])->name('transactions.report');
    
    // Visitor Monitoring Routes - accessible to all authenticated users
    Route::get('/visitor-monitoring', [VisitorMonitoringController::class, 'index'])->name('visitor-monitoring.index');
});

// API routes for ESP32-CAM integration
Route::prefix('api')->group(function () {
    Route::post('/visitor-entry', [VisitorMonitoringController::class, 'storeVisitorEntry'])->name('api.visitor.entry');
    Route::post('/visitor-exit/{id}', [VisitorMonitoringController::class, 'storeVisitorExit'])->name('api.visitor.exit');
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
