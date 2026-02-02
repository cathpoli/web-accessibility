<?php

namespace App\Providers;

use App\Listeners\SetupStripeCustomer;
use App\Listeners\Stripe\Coupons\CouponListener;
use App\Listeners\Stripe\Invoices\CreateOrUpdateInvoiceListener;
use App\Listeners\Stripe\Invoices\FailedPaymentListener;
use App\Listeners\Stripe\Invoices\SuccessfulPaymentListener;
use App\Listeners\Stripe\Payments\CreateOrUpdatePaymentListener;
use App\Listeners\Stripe\Subscriptions\CancellationListener;
use App\Listeners\Stripe\Subscriptions\CreatedListener;
use App\Listeners\Stripe\Subscriptions\RenewalListener;
use App\Listeners\Stripe\WebhookReceivedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SetupStripeCustomer::class,
        ],
        WebhookReceived::class => [
            WebhookReceivedListener::class,
            CreateOrUpdateInvoiceListener::class,
            FailedPaymentListener::class,
            SuccessfulPaymentListener::class,
            CreateOrUpdatePaymentListener::class,
            CreatedListener::class,
            RenewalListener::class,
            CancellationListener::class,
            CouponListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
