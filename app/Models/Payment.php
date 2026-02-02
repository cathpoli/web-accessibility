<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stripe_charge',
        'stripe_invoice',
        'user_id',
        'amount',
        'balance_transaction',
        'is_paid',
        'status', // values: https://stripe.com/docs/api/charges/object#charge_object-status
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
