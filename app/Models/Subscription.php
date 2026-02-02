<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    /** Note: Cashier Subscription model extension in case we need to customize */

    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        /**
         * Laravel\Cashier\Http\Controllers\WebhookController::handleCustomerSubscriptionUpdated
         * cashier deletes subscriptions with incomplete_expired status
         */

        static::deleted(function (Subscription $subscription) {
            $domain = $subscription->domain;

            if ($domain) {
                // also delete domain
                $domain->delete();

                // OR restore deleted instances
                // $subscription->restore();
                // $subscription->items()->restore();

                Log::warning("{$domain->name} deleted as result of incomplete_expired subscription status, kindly review if this is a mistake.");
            }
        });
    }

    public function domain()
    {
        return $this->hasOne(Domain::class, 'id', 'subscription_id');
    }
}
