<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'final_amount',
        'payment_method',
        'payment_status',
        'customer_name',
        'customer_phone',
        'customer_email',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $date = now()->format('Ymd');
        $lastTransaction = self::where('invoice_number', 'like', $prefix . $date . '%')
            ->latest()
            ->first();

        $sequence = '001';
        if ($lastTransaction) {
            $lastSequence = substr($lastTransaction->invoice_number, -3);
            $sequence = str_pad((int)$lastSequence + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . '-' . $sequence;
    }
}