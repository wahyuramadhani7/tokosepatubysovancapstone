<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\DashboardController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Product endpoints
    Route::get('/products', [InventoryController::class, 'index']);
    Route::post('/products', [InventoryController::class, 'store']);
    Route::get('/products/{id}', [InventoryController::class, 'show']);
    Route::get('/products/{id}/json', [InventoryController::class, 'json']);
    Route::get('/products/{productId}/units/{unitCode}', [InventoryController::class, 'showUnit']);
    Route::put('/products/{id}', [InventoryController::class, 'update']);
    Route::delete('/products/{id}', [InventoryController::class, 'destroy']);
    
    // Stock opname endpoints
    Route::post('/products/{id}/physical-stock', [InventoryController::class, 'updatePhysicalStock']);
    Route::get('/stock-opname', [InventoryController::class, 'stockOpname']);
    Route::post('/stock-opname/save', [InventoryController::class, 'saveReport']);
    Route::delete('/stock-opname/{index}', [InventoryController::class, 'deleteReport']);
    Route::delete('/stock-opname', [InventoryController::class, 'deleteAllReports']);

    // Dashboard endpoint
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

?>