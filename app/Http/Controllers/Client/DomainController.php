<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Domains\SubscribeRequest;
use App\Models\Card;
use App\Models\Coupon;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Product;
use App\Rules\ValidateDomainName;
use App\Rules\ValidateIfHasScheme;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\StripeClient;
use App\Services\Helper\Helper;
use App\Services\Product\ProductService;

class DomainController extends Controller
{
    public function index(Request $request) : Response
    {
        $domains = Domain::with(['subscription:id,stripe_status', 'product:id,name'])
                            ->where('user_id', $request->user()->id)
                            ->get();

        return Inertia::render('Client/Domains/Index', [
            'plans' => Product::active()->orderBy('price')->get(),
            'domains' => $domains,
        ]);
    }

    public function show(Domain $domain, Request $request) : Response
    {
        $secretKey = config('cashier.secret');
        $stripe = new StripeClient($secretKey);
        $coupon = [];
        $params = $request->query();
        $limit = $request->query('size', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.
        $orderBy = $params['orderBy'] ?? 'created_at'; // Default value if 'orderBy' parameter is not present
        $orderByDirection = $params['sort'] ?? 'desc'; // Default value if 'orderBy' parameter is not present
        $coupon = [];

        /** @return \Stripe\Subscription */
        $subscription = $stripe->subscriptions->retrieve($domain->subscription->stripe_id);

        $paymentMethod = Card::select('id', 'brand', 'last4', 'exp_month', 'exp_year', 'billing_name')
                                ->where('stripe_payment_method', $subscription->default_payment_method)
                                ->first();

        $invoicesQuery = Invoice::select('id', 'number', 'stripe_invoice', 'period_start', 'period_end', 'subtotal', 'total', 'status')
                                ->where('stripe_subscription', $subscription->id);

        if ($params && isset($params['sort']) && ($params['sort'] == 'asc' || $params['sort'] == 'desc')) {
            $invoicesQuery->orderBy($orderBy, $orderByDirection);
        }

        $invoices = $invoicesQuery->paginate($limit);

        if ($subscription->discount) {
            $coupon = Coupon::where('code', $subscription->discount->coupon->id)->first();
        }

        return Inertia::render('Client/Domains/Show', [
            'domain' => $domain->load('product:id,name,price,interval,page_range_end'),
            'subscription' => $subscription,
            'paymentMethod' => $paymentMethod,
            'invoices' => $invoices,
            'plans' => Product::active()->orderBy('price')->get(),
            'coupon' => $coupon,
            'planPages' => Helper::getPlanPages()
        ]);
    }
    public function validateNewDomain(Request $request)
    {
        /** Domain name regex validation: https://stackoverflow.com/a/26987741 */
        /** For domain and subdomain: https://stackoverflow.com/a/49134699 */
        $validator = Validator::make($request->all(), [
            'name' => [
                'bail',
                'required',
                new ValidateIfHasScheme,
                new ValidateDomainName,
                'regex:/^([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)?([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)?([a-zA-Z0-9]{1,2}([-a-zA-Z0-9]{0,252}[a-zA-Z0-9])?)\.([a-zA-Z]{2,63})$/',
            ]
        ], [
            'name.regex' => "The domain format is invalid."
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function subscribe(SubscribeRequest $request)
    {
        $user = $request->user();

        if ($request->user()->type == 'member') {
            $user = $request->user()->owner;
        }

        /** @return \Laravel\Cashier\PaymentMethod */
        $paymentMethod = $user->defaultPaymentMethod();

        /** @return \Stripe\PaymentMethod */
        $stripePaymentMethod = $paymentMethod->asStripePaymentMethod();

        // make sure user has setup payment method
        if (! $paymentMethod || $user->stripe_id === null) {
            throw new Exception('Please add payment method first.');
        }

        $product = Product::find($request->plan_id);
        $coupon = Coupon::firstWhere('code', $request->coupon);

        if ($coupon && ($coupon->type === 'period_off' && $product->interval !== 'year')) {
            throw new Exception('Coupon is only valid for annual plans.');
        }

        // make sure plan exists
        if (! $product) {
            throw new Exception('Selected plan does not exists.');
        }

        try {
            /** create domain model instance to get */
            $domain = Domain::create();
            $domain->name = Domain::formatHostname($request->name);
            $domain->user_id = $user->id;
            $domain->product_id = $product->id;
            $domain->page_count = $request->page_count;
            $domain->save();

            /** @return \Laravel\Cashier\SubscriptionBuilder */
            $builder = $user->newSubscription('default', $product->stripe_default_price);

            // apply coupon
            if ($coupon) $builder = Coupon::setCoupon($coupon->code, $builder);

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

         // forget session for selected plan
         $request->session()->forget('register_coupon');

        $domain->load(['subscription:id,stripe_status', 'product:id,name']);

        return FacadesResponse::json(['success' => true, 'domain' => $domain]);
    }

    public function cancel(Domain $domain, Request $request)
    {
        try {
            $subscription = $domain->subscription;

            /** @return \Laravel\Cashier\Subscription */
            $subscription = $subscription->cancel();

            // $domain->update(['cancelled_at' => $currentPeriodEnd]);

            // NOTE: domain next_payment_at is handle at stripe webhook

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            throw ValidationException::withMessages(['message' => 'Something went wrong.']);
        }

        return Redirect::route('domains.show', $domain->id);
    }

    public function resume(Domain $domain, Request $request)
    {
        try {
            $subscription = $domain->subscription;

            /** @return \Laravel\Cashier\Subscription */
            $subscription = $subscription->resume();

            // $domain->update(['cancelled_at' => null]);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            throw ValidationException::withMessages(['message' => 'Something went wrong.']);
        }

        return Redirect::route('domains.show', $domain->id);
    }

    public function changePlan(Domain $domain, Request $request)
    {
        $subscription = $domain->subscription;

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:products,id'
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages(['message' => $validator->errors()->first()]);
        }

        try {
            $product = Product::find($request->plan_id);

            /** @return \Laravel\Cashier\Subscription */
            /** Read about prorations: https://stripe.com/docs/billing/subscriptions/prorations */
            $subscription = $subscription->noProrate()->swap($product->stripe_default_price);

            // assign domain the new product
            $domain->update(['product_id' => $product->id]);
            $domain->update(['product_id' => $product->id, 'page_count' => $request->page_count]);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            throw ValidationException::withMessages(['message' => 'Something went wrong.']);
        }

        return Redirect::route('domains.show', $domain->id);
    }

    public function getPlans(Request $request)
    {
        try {
            $pageCount = Helper::getSitePageCount($request->domain);
            $plans = ProductService::getPlan($pageCount);
            
            return json_encode(['plans' => $plans, 'page_count' => $pageCount], true);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            
            return json_encode([], false);
        }
    }
}
