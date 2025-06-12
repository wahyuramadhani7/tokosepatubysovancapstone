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

// Public routes for product details and QR code printing
Route::prefix('inventory')->group(function () {
    Route::get('/{product}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::get('/{product}/print-qr', [InventoryController::class, 'printQr'])->name('inventory.print_qr');
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
        Route::get('/history', [InventoryController::class, 'history'])->name('inventory.history');
        Route::get('/search', [InventoryController::class, 'search'])->name('inventory.search');
        Route::get('/{product}/json', [InventoryController::class, 'json'])->name('inventory.json');
        Route::get('/{product}/verify', [InventoryController::class, 'verifyStockForm'])->name('inventory.verify');
        Route::post('/{product}/verify', [InventoryController::class, 'verifyStock'])->name('inventory.verify_stock');
        Route::get('inven/scan-qr', [InventoryController::class, 'scanQr'])->name('inventory.scan_qr');
        Route::post('/handle_qr_scan', [InventoryController::class, 'handleQrScan'])->name('inventory.handle_qr_scan');
    });

    // Transactions Routes
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');

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
?>