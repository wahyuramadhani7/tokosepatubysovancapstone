<?php

namespace App\Observers;

use App\Models\ProductUnit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ProductUnitObserver
{
    private function userKey($key)
    {
        $userId = Auth::id() ?? 'guest';
        return "user_{$userId}_{$key}";
    }

    public function created(ProductUnit $unit): void
    {
        $this->clearProductCache($unit->product_id);
    }

    public function updated(ProductUnit $unit): void
    {
        Cache::forget("unit_{$unit->product_id}_{$unit->unit_code}");
        $this->clearProductCache($unit->product_id);
    }

    public function deleted(ProductUnit $unit): void
    {
        Cache::forget("unit_{$unit->product_id}_{$unit->unit_code}");
        $this->clearProductCache($unit->product_id);
    }

    private function clearProductCache($productId): void
    {
        Cache::forget("product_{$productId}");
        Cache::forget($this->userKey('all_brand_counts'));
        Cache::forget($this->userKey('new_units_' . $productId));
    }
}