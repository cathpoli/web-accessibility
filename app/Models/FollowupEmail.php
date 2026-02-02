<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowupEmail extends Model
{
    /** @return \Laravel\Cashier\PaymentMethod */

    use HasFactory;

    public const TYPE_REGISTER = 'register';

    protected $fillable = [
        'user_id',
        'type',
    ];
}
