<?php

namespace App\Listeners\Stripe\Subscriptions;

use App\Models\Domain;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreatedListener
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

        if ($eventType === 'customer.subscription.created') {
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

            /** attach subscription to the domain along with the next_payment_at */
            $domainId = $eventObject['metadata']['domain_id'];
            $nextPaymentAt = Carbon::createFromTimestamp($eventObject['current_period_end']);
            $domain = Domain::find($domainId);

            if (! $domain) {
                Log::error("[{$eventType}] {$event->payload['id']} : domain with id {$domainId} does not exists.");
                return;
            };

            $domain->update([
                'subscription_id' => $subscription->id,
                'next_payment_at' => $nextPaymentAt,
            ]);
        }
    }
}
