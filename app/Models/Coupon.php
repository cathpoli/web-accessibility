<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Coupon extends Model
{
    /**
     * IMPORTANT: period_off coupon type are custom types we just created, they are not created on the stripe dashboard.
     *      We're using the combination of billing_cycle_anchor and prorations to achieve this. Billing Cycle for setting the start
     *      of invoicing in the future and prorations to avoid charging the customer on subscription.
     */

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'percent_off',
        'amount_off',
        'period_off',
        'duration',
        'times_redeemed',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public static function generateUniqueCouponCode($length = 8) : string
    {
        while (true) {
            $code = Str::random($length);

            if (! Coupon::where('code', $code)->exists()); break;
        }

        return $code;
    }

    public static function setCoupon(string $code, $builder)
    {
        $coupon = Coupon::where('code', $code)->first();

        if ($coupon->type != 'period_off') {
            return $builder->withCoupon($coupon->code);
        }

        /**
         * References for Billing Cycle and Prorations:
         *
         * https://stripe.com/docs/billing/subscriptions/billing-cycle
         * https://laravel.com/docs/8.x/billing#subscription-anchor-date
         * https://laravel.com/docs/8.x/billing#prorations
         * https://stripe.com/docs/billing/subscriptions/prorations
         */
        $period = Carbon::today()->addMonths($coupon->period_off);
        return $builder->noProrate()->anchorBillingCycleOn($period);
    }
}
