<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockVerification extends Model
{
    protected $fillable = ['product_id', 'system_stock', 'physical_stock', 'discrepancy', 'notes', 'user_id'];
}