<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    protected $table = 'product_histories';
    protected $fillable = [
        'type',
        'product_id',
        'brand',
        'model',
        'size',
        'color',
        'stock',
        'stock_change',
        'selling_price',
        'discount_price',
        'user_id',
        'user_name',
        'timestamp',
    ];

    // Nonaktifkan timestamps default
    public $timestamps = false;
}