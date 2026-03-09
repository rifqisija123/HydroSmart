<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'code_transactions',
        'transaction_type',
        'drink',
        'ml',
        'amount',
        'status',
        'issuer',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];
}
