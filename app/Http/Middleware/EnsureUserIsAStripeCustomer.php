<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class EnsureUserIsAStripeCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /** proceed if guest */
        if (! $request->user()) return $next($request);

        /** allow user to logout event if not subscribed or has no card */
        if (in_array(Route::current()->uri(), ['card-details', 'plans', 'finish', 'thank-you', 'logout'])) {
            return $next($request);
        }

        /** no need to check admin and member user */
        if ($request->user()->type === 'admin' || $request->user()->type === 'member') {
            return $next($request);
        }

        /**
         * if user does not have default payment method, it means they most likely did not go through the thank you page of the registration process
         * NOTE: stripeCustomer is created on first step of registration
         */
        if ($request->user()->defaultPaymentMethod() === null) {
            return redirect('/plans');
        }

        return $next($request);
    }
}
