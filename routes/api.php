<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Product endpoints
    Route::get('/products', [InventoryController::class, 'index']);
    Route::post('/products', [InventoryController::class, 'store']);
    Route::get('/products/{id}', [InventoryController::class, 'show']);
    Route::get('/products/{id}/json', [InventoryController::class, 'json']);
    Route::get('/products/{productId}/units/{unitCode}', [InventoryController::class, 'showUnit']);
    Route::get('/units', [InventoryController::class, 'getUnits']); // New endpoint for units
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

    // Dashboard endpoint
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// Catch-all for invalid routes to return JSON
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route not found',
    ], 404);
});