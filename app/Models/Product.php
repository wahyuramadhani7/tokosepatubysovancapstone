<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'size',
        'selling_price',
        'discount_price',
    ];

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getStockAttribute()
    {
        return $this->productUnits()->where('is_active', true)->count();
    }
}