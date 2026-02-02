<?php

namespace App\Console\Commands\Stripe;

use App\Models\Coupon;
use App\Models\CouponProduct;
use App\Models\Product;
use Illuminate\Console\Command;
use Stripe\StripeClient;

class SyncCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all coupons from stripe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $secretKey = config('cashier.secret');
        $stripe = new StripeClient($secretKey);

        $couponsRaw = $stripe->coupons->all(['expand' => ['data.applies_to']]);
        $coupons = $couponsRaw['data'];

        foreach ($coupons as $key => $stripeCoupon) {
            $coupon = Coupon::firstOrNew(['code' => $stripeCoupon->id]);
            $coupon->name = $stripeCoupon->name;
            $coupon->type = $stripeCoupon->percent_off != null ? 'percent_off' : 'amount_off';
            $coupon->percent_off = $stripeCoupon->percent_off ?? null;
            $coupon->amount_off = $stripeCoupon->amount_off ? $stripeCoupon->amount_off / 100 : null;
            $coupon->duration = $stripeCoupon->duration;
            $coupon->times_redeemed = $stripeCoupon->times_redeemed;
            $coupon->save();

            // check if coupon is for specific product
            if (isset($stripeCoupon->applies_to)) {
                foreach ($stripeCoupon->applies_to['products'] as $key => $stripeProductId) {
                    $product = Product::where('stripe_product', $stripeProductId)->first();
                    if (! $product) continue;

                    // check if already saved
                    $pivot = CouponProduct::firstOrNew(['coupon_id' => $coupon->id, 'product_id' => $product->id]);
                    $pivot->coupon_id = $coupon->id;
                    $pivot->product_id = $product->id;
                    $pivot->save();
                }
            }
        }

        return Command::SUCCESS;
    }
}
