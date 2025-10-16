<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'product_name',
        'size',
        'color',
        'original_price',
        'discount_price',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}