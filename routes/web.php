<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VisitorMonitoringController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Public routes for product details without rate limiting
Route::prefix('inventory')->group(function () {
    Route::get('/{product}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::get('/{product}/json', [InventoryController::class, 'json'])->name('inventory.json');
});

// Routes for employee dashboard
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
});

// Routes for owner dashboard and employee management
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');
    Route::get('/owner/employee-accounts', [DashboardController::class, 'employeeAccounts'])->name('owner.employee-accounts');
    Route::get('/owner/employee-accounts/create', [DashboardController::class, 'createEmployee'])->name('owner.employee-accounts.create');
    Route::post('/owner/employee-accounts', [DashboardController::class, 'storeEmployee'])->name('owner.employee-accounts.store');
    Route::get('/owner/employee-accounts/{user}/edit', [DashboardController::class, 'editEmployee'])->name('owner.employee-accounts.edit');
    Route::put('/owner/employee-accounts/{user}', [DashboardController::class, 'updateEmployee'])->name('owner.employee-accounts.update');
    Route::delete('/owner/employee-accounts/{user}', [DashboardController::class, 'deleteEmployee'])->name('owner.employee-accounts.destroy');
});

// Routes for inventory, transactions, and visitor monitoring (accessible to all authenticated users)
Route::middleware(['auth'])->group(function () {
    // Inventory Routes
    Route::prefix('inventory')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/{product}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
        Route::put('/{product}', [InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('/{product}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::get('inventory/search', [InventoryController::class, 'search'])->name('inventory.search');
        Route::get('/{product}/print-qr', [InventoryController::class, 'printQr'])->name('inventory.print_qr');
        Route::post('/{product}/physical-stock', [InventoryController::class, 'updatePhysicalStock'])->name('inventory.updatePhysicalStock');
    });

    // Transactions Routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
        Route::get('/add-product/{product}', [TransactionController::class, 'addProductByQr'])->name('transactions.add-product-by-qr');
    });

    // Transaction Reports
    Route::get('/transactions/reports/sales', [TransactionController::class, 'report'])->name('transactions.report');

    // Visitor Monitoring Routes
    Route::get('/visitor-monitoring', [VisitorMonitoringController::class, 'index'])->name('visitor-monitoring.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API routes for ESP32-CAM integration
Route::prefix('api')->group(function () {
    Route::post('/visitor-entry', [VisitorMonitoringController::class, 'storeVisitorEntry'])->name('api.visitor.entry');
    Route::post('/visitor-exit/{id}', [VisitorMonitoringController::class, 'storeVisitorExit'])->name('api.visitor.exit');
});

// Main dashboard route after login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Include authentication routes
require __DIR__ . '/auth.php';

// Catch-all route for invalid URLs
Route::get('/{any}', function () {
    return redirect()->route('login');
})->where('any', '.*')->middleware('guest');