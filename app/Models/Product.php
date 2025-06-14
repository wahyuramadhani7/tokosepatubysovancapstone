<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code',
        'name',
        'color',
        'size',
        'stock',
        'purchase_price',
        'selling_price',
    ];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
    public function stockVerifications()
    {
        return $this->hasMany(StockVerification::class, 'product_id');
    }
}