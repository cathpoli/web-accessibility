<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\SubscriptionItem as CashierSubscriptionItem;

class SubscriptionItem extends CashierSubscriptionItem
{
    /** Note: Cashier SubscriptionItem model extension in case we need to customize */

    use HasFactory, SoftDeletes;
}
