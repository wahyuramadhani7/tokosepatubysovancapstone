<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\VisitorMonitoringController;
use App\Http\Controllers\API\VisitorController;

Route::post('/login', [AuthController::class, 'login']);

// Route untuk ESP (tanpa auth:sanctum, validasi via API key di controller)
Route::post('/visitor-entry', [VisitorMonitoringController::class, 'storeVisitorEntry']);
Route::post('/visitor-exit/{id}', [VisitorMonitoringController::class, 'storeVisitorExit']);

// Route yang memerlukan autentikasi Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Product endpoints
    Route::get('/products', [InventoryController::class, 'index']);
    Route::post('/products', [InventoryController::class, 'store']);
    Route::get('/products/{id}', [InventoryController::class, 'show']);
    Route::get('/products/{id}/json', [InventoryController::class, 'json']);
    Route::get('/products/{productId}/units/{unitCode}', [InventoryController::class, 'showUnit']);
    Route::get('/units', [InventoryController::class, 'getUnits']);
    Route::put('/products/{id}', [InventoryController::class, 'update']);
    Route::delete('/products/{id}', [InventoryController::class, 'destroy']);
    
    // Stock opname endpoints
    Route::post('/products/{id}/physical-stock', [InventoryController::class, 'updatePhysicalStock']);
    Route::get('/stock-opname', [InventoryController::class, 'stockOpname']);
    Route::post('/stock-opname/save', [InventoryController::class, 'saveReport']);
    Route::delete('/stock-opname/{index}', [InventoryController::class, 'deleteReport']);
    Route::delete('/stock-opname', [InventoryController::class, 'deleteAllReports']);

    // Transaction endpoints
    Route::get('/transactions', [TransactionController::class, 'index'])->name('api.transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('api.transactions.store');
    Route::get('/transactions/add-product/{unitCode}', [TransactionController::class, 'addProduct'])->name('api.transactions.add-product');

Route::post('/visitor/update', [VisitorController::class, 'updateStatus']);
Route::get('/visitor/dashboard', [VisitorController::class, 'getDashboardData']);

    // Dashboard endpoint
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route not found',
    ], 404);
});