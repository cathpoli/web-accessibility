<?php

namespace App\Http\Controllers\Client\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\SelectRequest;
use App\Http\Requests\Products\SubscribeRequest;
use App\Models\Coupon;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use App\Services\Helper\Helper;
use App\Services\Product\ProductService;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $domain = Domain::firstWhere('user_id', $user->id);
        $plans = ProductService::getPlan($domain->page_count);

        return Inertia::render('Client/Auth/Register/Products', [
            'plans' => $plans
        ]);
    }

    public function select(SelectRequest $request)
    {
        $product = Product::firstWhere('slug', $request->slug);
        $coupon = Coupon::firstWhere('code', $request->coupon);

        if ($coupon) {
            if ($coupon->type === 'period_off' && $product->interval !== 'year') {
                throw ValidationException::withMessages(['coupon' => 'Coupon is only valid for annual plans.']);
            }

            $request->session()->put('register_coupon', $coupon->code);
        }

        $request->session()->put('register_product', $product);

        return redirect('card-details');
    }

    public function subscribe(SubscribeRequest $request)
    {
        $user = $request->user();

        /** @return \Laravel\Cashier\PaymentMethod */
        $paymentMethod = $user->defaultPaymentMethod();

        /** @return \Stripe\PaymentMethod */
        $stripePaymentMethod = $paymentMethod->asStripePaymentMethod();

        // make sure user has setup payment method
        if (! $paymentMethod || $user->stripe_id === null) {
            throw new Exception('Please add payment method first.');
        }

        $product = Product::firstWhere('slug', $request->slug);

        // make sure plan exists
        if (! $product) {
            throw new Exception('Selected plan does not exists.');
        }

        try {
            /** get the domain model instance */
            $domain = Domain::firstWhere('user_id', $user->id);

            /** set the current plan to the domain table */
            $domain->product_id = $product->id;
            $domain->save();

            /** @return \Laravel\Cashier\SubscriptionBuilder */
            $builder = $user->newSubscription('default', $product->stripe_default_price);

            if ($request->coupon) {
                $builder = $builder->withCoupon($request->coupon);
            }

            /** @return \Laravel\Cashier\Subscription */
            $subscription = $builder->create($stripePaymentMethod, [], [
                        'default_payment_method' => $stripePaymentMethod,
                        'metadata' => [
                            'domain_id' => $domain->id,
                            'domain' => $domain->name,
                        ]
                    ]);

            /**
             * Note: We need to attach the subscription to the domain. but in some cases that invoice payment fails.
             *          So we will handle that in the CreatedListener on the stripe webhook
            */

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            throw new Exception('Something went wrong.');
        }

        $domain->load(['subscription:id,stripe_status', 'product:id,name']);

        return Redirect::route('register.thank-you');
    }
}
