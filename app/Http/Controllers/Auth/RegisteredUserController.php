<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Domain;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\ValidateDomainName;
use App\Rules\ValidateIfHasScheme;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\Helper\Helper;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Client/Auth/Register/BasicInformation', [
            'loginDomain' => config('app.dashboard_domain')
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:'.User::class, // rfc,dns reference: https://laravel.com/docs/9.x/validation#rule-email
            'website_url' => [
                'bail',
                'required',
                new ValidateIfHasScheme,
                new ValidateDomainName,
                'regex:/^([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)?([a-zA-Z0-9]{1,2}([-a-zA-Z0-9]{0,252}[a-zA-Z0-9])?)\.([a-zA-Z]{2,63})$/',
                'unique:domains,name',
            ],
            'billing_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'recaptcha_token' => 'required',
        ], [
            'recaptcha_token.required' => 'Please verify that you are not a robot.'
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'billing_address'   => $request->billing_address,
            'billing_address_2' => $request->billing_address_2,
            'billing_address_3' => $request->billing_address_3,
            'city'              => $request->city,
            'state'             => $request->state,
            'zip'               => $request->zip,
            'country'           => $request->country,
            'phone_number'      => $request->phone_number,
            'password'          => Hash::make($request->password),
        ]);

        // Save the domain to the domains tables
        if ($request->website_url) {
            $domain = Domain::create();
            $domain->name = Domain::formatHostname($request->website_url);
            $domain->user_id = $user->id;
            $domain->page_count = Helper::getSitePageCount($domain->name);
            $domain->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function finish(Request $request)
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

        $product = $request->session()->get('register_product');

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

            if ($request->session()->exists('register_coupon')) {
                $builder = Coupon::setCoupon($request->session()->get('register_coupon'), $builder);
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

        // forget session for selected plan on register
        $request->session()->forget('register_coupon');
        $request->session()->forget('register_product');

        $domain->load(['subscription:id,stripe_status', 'product:id,name']);

        return Redirect::route('register.thank-you');
    }

    public function thankYou(Request $request)
    {
        $user = User::find(Auth::id());

        // set tapfiliate customer_id
        $customerId = Str::uuid();
        $user->tapfiliate_customer_id = $customerId;
        $user->save();

        // logout user before redirecting to admin.acecomply.com
        Auth::logout();

        return Inertia::render('Client/Auth/Register/ThankYou', [
            'redirect' => config('app.dashboard_domain') . "/sso/{$user->sso_token}",
            'customerId' => $customerId,
        ]);
    }
}
