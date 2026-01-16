<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Helper untuk cache key per user
     */
    private function userKey($key)
    {
        $userId = Auth::id() ?? 'guest';
        return "user_{$userId}_{$key}";
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->clearRelatedCache($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->clearRelatedCache($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->clearRelatedCache($product);
        
        // Khusus delete: bersihkan cache tambahan
        Cache::forget("product_{$product->id}");
        Cache::forget($this->userKey('new_units_' . $product->id));
        Cache::forget($this->userKey('stock_mismatches'));
    }

    /**
     * Bersihkan semua cache yang terkait dengan produk ini
     */
    private function clearRelatedCache(Product $product): void
    {
        Cache::forget("product_{$product->id}");
        Cache::forget($this->userKey('brand_names'));
        Cache::forget($this->userKey('new_products'));
        Cache::forget($this->userKey('updated_products'));
        Cache::forget($this->userKey('all_brand_counts')); // Kita tambahkan nanti di step berikutnya
    }
}