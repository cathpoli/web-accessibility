<?php

namespace App\Listeners\Stripe\Subscriptions;

use App\Models\Domain;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class RenewalListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        /** stripe events: https://stripe.com/docs/api/events/types */
        $eventType = $event->payload['type'];
        $eventObject = $event->payload['data']['object'];

        if ($eventType === 'customer.subscription.updated') {
            /** We can use the domain_id from the metadata but for simplicity we're just gonna use the subscription id */
            // $domainId = $eventObject['metadata']['domain_id'] ?? null;
            $subscriptionId = $eventObject['id'];

            // log caught listener for easy debugging
            // Log::info("[{$eventType}] {$event->payload['id']} : {$event->payload['data']['object']['id']}");

            $subscription = Subscription::where('stripe_id', $subscriptionId)->first();

            if (! $subscription) {
                Log::error("[{$eventType}] {$event->payload['id']} : subscription with id {$subscriptionId} does not exists.");
                return;
            };

            // set new next_payment_at value
            $nextPaymentAt = Carbon::createFromTimestamp($eventObject['current_period_end']);
            $domain = Domain::where('subscription_id', $subscription->id)->update(['next_payment_at' => $nextPaymentAt]);
        }
    }
}
