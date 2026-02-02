<?php

namespace App\Listeners\Stripe\Invoices;

use App\Mail\Invoices\FailedPaymentMail;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FailedPaymentListener
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

        /**
         * Reference: https://stripe.com/docs/api/events/types#event_types-invoice.payment_failed
         * We're gonna use the `invoice.payment_failed` event for checking if the payment has failed
         * Another event we can use is `charge.failed`
         */

        if ($eventType === 'invoice.payment_failed') {
            $customerStripeId = $eventObject['customer'];
            $invoiceId = $eventObject['id'];

            $user = User::where('stripe_id', $customerStripeId)->first();
            $invoice = Invoice::where('stripe_invoice', $invoiceId)->first();

            // log caught listener for easy debugging
            // Log::info("[{$eventType}] {$event->payload['id']} : {$event->payload['data']['object']['id']}");

            if (! $user) {
                Log::error("[{$eventType}] {$event->payload['id']} : user with stripe_id {$customerStripeId} does not exists.");
                return;
            }

            if (! $invoice) {
                Log::error("[{$eventType}] {$event->payload['id']} : invoice with id {$invoiceId} does not exists.");
                return;
            }

            Mail::to($user)->send(new FailedPaymentMail($invoiceId));
        }
    }
}
