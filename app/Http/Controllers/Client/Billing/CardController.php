<?php

namespace App\Http\Controllers\Client\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cards\StoreRequest;
use App\Models\Card;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Stripe\StripeClient;

class CardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        /** set page to view depending on the referer of the request */
        $page = 'Client/Billing/Cards/Add';
        $referer = FacadesRequest::header('referer');

        if (! $referer || Str::contains(FacadesRequest::header('referer'), 'plans')) {
            $page = 'Client/Auth/Register/CardDetails';
        }

        /** refirect to plans if selected product from session has expired */
        if ($page === 'Client/Auth/Register/CardDetails' && ! $request->session()->exists('register_product')) {
            return redirect('/plans');
        }

        return Inertia::render($page, [
            'stripe_key' => config('cashier.key'),
            'intent' => $user->createSetupIntent()
        ]);
    }

    public function store(StoreRequest $request)
    {
        $user = $request->user();

        /** @return \Laravel\Cashier\PaymentMethod */
        if ($request->set_as_default) {
            $paymentMethod = $user->updateDefaultPaymentMethod($request->payment_method);
        } else {
            $paymentMethod = $user->addPaymentMethod($request->payment_method);
        }

        /** Use this method to sync default payment method from stripe */
        // $user->updateDefaultPaymentMethodFromStripe();

        /** @return \Stripe\PaymentMethod */
        // $stripePaymentMethod = $paymentMethod->asStripePaymentMethod();

        $card = new Card();
        $card->user_id = $user->id;
        $card->stripe_payment_method = $paymentMethod->id;
        $card->brand = $paymentMethod->card->brand;
        $card->last4 = $paymentMethod->card->last4;
        $card->exp_month = $paymentMethod->card->exp_month;
        $card->exp_year = $paymentMethod->card->exp_year;
        $card->save();

        if ($request->set_as_default) {
            $this->updateAllSubscriptionsPaymentMethod($user, $card);
        }

        /** set route where user should be redirected */
        $route = Str::contains(FacadesRequest::header('referer'), 'billing/cards/create') ? 'billing.index' : 'register.finish';

        return redirect()->route($route);
    }

    public function destroy(Card $card, Request $request)
    {
        $user = $request->user();

        $defaultPaymentMethod =  $user->defaultPaymentMethod();

        /** @return \Laravel\Cashier\PaymentMethod */
        $paymentMethod = $user->findPaymentMethod($card->stripe_payment_method);

        /** Throw error if use is trying to delete default payment method */
        if ($defaultPaymentMethod->id === $paymentMethod->id) {
            throw new Exception('Cannot delete default payment method');
        }

        /** @return \Stripe\PaymentMethod */
        $deletedPaymentMethod = $paymentMethod->delete();

        $card->delete();

        return Redirect::route('billing.index');
    }

    public function setAsDefault(Card $card, Request $request)
    {
        $user = $request->user();

        DB::beginTransaction();

        try {
            /** @return \Laravel\Cashier\PaymentMethod */
            $paymentMethod = $user->findPaymentMethod($card->stripe_payment_method);

            $this->updateAllSubscriptionsPaymentMethod($user, $card);

            /** @return \Laravel\Cashier\PaymentMethod */
            $user->updateDefaultPaymentMethod($paymentMethod->id);

            DB::commit();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            DB::rollBack();
            throw ValidationException::withMessages(['message' => 'Something went wrong.']);
        }

        return Redirect::route('billing.index');
    }

    public function updateAllSubscriptionsPaymentMethod(User $user, Card $card)
    {
        /** update all active subscriptions' default payment method */
        $secretKey = config('cashier.secret');
        $stripe = new StripeClient($secretKey);

        /** @return \Laravel\Cashier\Subscription */
        $subscriptions = $user->subscriptions()->active()->get();

        foreach ($subscriptions as $key => $stripeSubscription) {
            $stripe->subscriptions->update($stripeSubscription->stripe_id, [
                'default_payment_method' => $card->stripe_payment_method
            ]);
        }
    }
}
