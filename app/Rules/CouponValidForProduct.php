<?php

namespace App\Rules;

use App\Models\Coupon;
use App\Models\CouponProduct;
use App\Models\Product;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;

class CouponValidForProduct implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! $value) return true;

        $coupon = Coupon::where('code', $value)->first();

        // for non product specific coupons
        if ($coupon->products()->count() === 0) return true;

        $slug = request()->get('slug');
        $product = Product::where('slug', $slug)->first();

        return CouponProduct::where('coupon_id', $coupon->id)
                        ->where('product_id', $product->id)
                        ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Coupon is not valid for the selected plan.';
    }
}
