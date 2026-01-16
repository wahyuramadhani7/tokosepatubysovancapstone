<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;         // ← Tambahkan
use App\Models\ProductUnit;    // ← Tambahkan
use App\Observers\ProductObserver;     // ← Tambahkan
use App\Observers\ProductUnitObserver; // ← Tambahkan

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan observer agar otomatis dijalankan saat ada event
        // created, updated, deleted pada model Product dan ProductUnit
        Product::observe(ProductObserver::class);
        ProductUnit::observe(ProductUnitObserver::class);
    }
}