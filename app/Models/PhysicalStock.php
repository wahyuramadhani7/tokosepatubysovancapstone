<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalStock extends Model
{
    protected $fillable = ['product_id', 'physical_stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}