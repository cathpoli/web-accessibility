<?php

namespace App\Listeners\Stripe\Payments;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateOrUpdatePaymentListener
{
    /** https://stripe.com/docs/api/events/types#event_types-charge.captured */
    private const CHARGE_EVENTS = [
        'charge.captured',
        'charge.expired',
        'charge.failed',
        'charge.pending',
        'charge.succeeded',
        'charge.updated',
    ];

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

        if (in_array($eventType, self::CHARGE_EVENTS)) {
            $customerStripeId = $eventObject['customer'];
            $user = User::where('stripe_id', $customerStripeId)->first();

            // log caught listener for easy debugging
            // Log::info("[{$eventType}] {$event->payload['id']} : {$event->payload['data']['object']['id']}");

            if (! $user) {
                Log::error("[{$eventType}] {$event->payload['id']} : user with stripe_id {$customerStripeId} does not exists.");
                return;
            }

            $payment = Payment::updateOrCreate(
                ['stripe_charge' => $eventObject['id']],
                [
                    'stripe_invoice' => $eventObject['invoice'],
                    'user_id' => $user->id ?? null,
                    'amount' => $eventObject['amount'] / 100,
                    'balance_transaction' => $eventObject['balance_transaction'],
                    'is_paid' => $eventObject['paid'],
                    'status' => $eventObject['status'],
                ]
            );
        }
    }
}
