<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VisitorMonitoringController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PurchaseNoteController;

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

// Redirect root to appropriate dashboard based on role or login if guest
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif (Auth::user()->role === 'employee') {
            return redirect()->route('employee.dashboard');
        }
        // Fallback for users with no valid role
        return redirect()->route('login');
    }
    return redirect()->route('login');
});

// Public routes for product and unit details without rate limiting
Route::prefix('inventory')->group(function () {
    Route::get('/{product}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::get('/{product}/unit/{unitCode}', [InventoryController::class, 'showUnit'])->name('inventory.show_unit');
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
        Route::get('inventory/stock-opname', [InventoryController::class, 'stockOpname'])->name('inventory.stock_opname');
        Route::post('inventory/update-stock', [InventoryController::class, 'updateStock'])->name('inventory.update_stock');
        Route::post('/inventory/save-report', [InventoryController::class, 'saveReport'])->name('inventory.save_report');
        Route::delete('/inventory/delete-report/{index}', [InventoryController::class, 'deleteReport'])->name('inventory.delete_report');
        Route::delete('inventory/delete-all-reports', [InventoryController::class, 'deleteAllReports'])->name('inventory.delete_all_reports');
        Route::get('/inventory/status', [InventoryController::class, 'status'])->name('inventory.status');
        Route::get('/inventory/history', [InventoryController::class, 'history'])->name('inventory.history');
        Route::post('/inventory/{productId}/unit/{unitCode}/sell', [InventoryController::class, 'sellUnit'])->name('inventory.sell_unit');
        Route::get('/inventory/purchase-notes', [InventoryController::class, 'purchaseNotes'])->name('inventory.purchase_notes');
    });

    // Transactions Routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
        Route::get('/transactions/fetch', [TransactionController::class, 'fetch'])->name('transactions.fetch');
        Route::get('transaction/report', [TransactionController::class, 'report'])->name('transactions.report');
        Route::get('/add-product/{unitCode}', [TransactionController::class, 'addProductByQr'])->name('transactions.add_product');
    });

    // Visitor Monitoring Routes
    Route::get('/visitor-monitoring', [VisitorMonitoringController::class, 'index'])->name('visitor-monitoring.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::resource('purchase_notes', PurchaseNoteController::class);
Route::get('/purchase-notes/export/pdf', [PurchaseNoteController::class, 'exportPdf'])->name('purchase_notes.export.pdf');
Route::get('/purchase-notes/export/excel', [PurchaseNoteController::class, 'exportExcel'])->name('purchase_notes.export.excel');

// API routes for ESP32-CAM integration
Route::prefix('api')->group(function () {
    Route::post('/visitor-entry', [VisitorMonitoringController::class, 'storeVisitorEntry'])->name('api.visitor.entry');
    Route::post('/visitor-exit/{id}', [VisitorMonitoringController::class, 'storeVisitorExit'])->name('api.visitor.exit');
});

// Include authentication routes
require __DIR__ . '/auth.php';

// Catch-all route for invalid URLs (only for unauthenticated users)
Route::fallback(function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif (Auth::user()->role === 'employee') {
            return redirect()->route('employee.dashboard');
        }
    }
    return redirect()->route('login');
})->middleware('guest');