<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameReport extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'brand',
        'model',
        'size',
        'color',
        'system_stock',
        'physical_stock',
        'difference',
        'scanned_qr_codes',
        'unscanned_qr_codes',
        'user_id',
        'user_name',
        'timestamp',
    ];

    protected $casts = [
        'scanned_qr_codes' => 'array',
        'unscanned_qr_codes' => 'array',
        'timestamp' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}