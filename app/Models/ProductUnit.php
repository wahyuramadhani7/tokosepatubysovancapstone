<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $fillable = ['product_id', 'unit_code', 'qr_code', 'is_active'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}