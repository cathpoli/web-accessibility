<?php

namespace App\Listeners\Stripe\Subscriptions;

use App\Jobs\Subscriptions\DeleteTestModeSubscriptionsJob;
use App\Models\Domain;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CancellationListener
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

        if ($eventType === 'customer.subscription.deleted') {
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

            // update subscription status
            $subscription->update(['stripe_status' => $eventObject['status']]);

            $domain = Domain::where('subscription_id', $subscription->id)->first();

            if (! $domain) {
                Log::error("[{$eventType}] {$event->payload['id']} : no domain found attached to subsctiption {$subscriptionId}.");
                return;
            };

            // set domain next_payment_at and cancelled_at
            $cancelledAt = Carbon::createFromTimestamp($eventObject['canceled_at']);
            $domain->update(['next_payment_at' => null, 'cancelled_at' => $cancelledAt]);

            /**
             * delete test mode subscriptions
             * reference: https://support.stripe.com/questions/test-mode-subscription-data-retention
             */
            if (! $event->payload['livemode']) {
                DeleteTestModeSubscriptionsJob::dispatch($domain->id)->delay(Carbon::now()->addDays(30));

                Log::warning("Test mode subscription scheduled for deletion: {$subscription->stripe_id} - {$domain->name}");
            }
        }
    }
}
