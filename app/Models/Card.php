<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    /** @return \Laravel\Cashier\PaymentMethod */

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'stripe_payment_method',
        'brand',
        'last4',
        'exp_month',
        'exp_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
