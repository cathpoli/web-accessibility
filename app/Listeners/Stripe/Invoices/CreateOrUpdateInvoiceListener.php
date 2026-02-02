<?php

namespace App\Listeners\Stripe\Invoices;

use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateOrUpdateInvoiceListener
{
    /** Events: https://stripe.com/docs/api/events/types#event_types-invoice.created */
    private const INVOICE_EVENTS = [
        'invoice.created',
        'invoice.deleted',
        'invoice.finalized',
        'invoice.finalization_failed',
        'invoice.marked_uncollectible',
        'invoice.paid',
        'invoice.payment_action_required',
        'invoice.payment_failed',
        'invoice.payment_succeeded',
        'invoice.sent',
        'invoice.upcoming',
        'invoice.updated',
        'invoice.voided',
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

        if (in_array($eventType, self::INVOICE_EVENTS)) {
            $subscriptionId = $eventObject['subscription'];
            $subscription = Subscription::where('stripe_id', $subscriptionId)->first();

            // log caught listener for easy debugging
            // Log::info("[{$eventType}] {$event->payload['id']} : {$event->payload['data']['object']['id']}");

            if (! $subscription) {
                Log::error("[{$eventType}] {$event->payload['id']} : subscription with id {$subscriptionId} does not exists.");
                return;
            }

            $user = User::find($subscription->user_id);
            $domain = Domain::where('subscription_id', $subscription->id)->first();
            $payment = Payment::where('stripe_invoice', $eventObject['id'])->first();

            $lineItems = $eventObject['lines']['data'];

            $invoice = Invoice::updateOrCreate(
                ['stripe_invoice' => $eventObject['id']],
                [
                    'user_id' => $user->id ?? null,
                    'domain_id' => $domain->id ?? null,
                    'payment_id' => $payment->id ?? null,
                    'number' => $eventObject['number'],
                    'stripe_customer' => $eventObject['customer'],
                    'stripe_subscription' => $eventObject['subscription'],
                    'stripe_charge' => $eventObject['charge'],
                    'period_start' => Carbon::createFromTimestamp($lineItems[0]['period']['start']),
                    'period_end' => Carbon::createFromTimestamp($lineItems[0]['period']['end']),
                    'subtotal' => $eventObject['subtotal'] / 100,
                    'total' => $eventObject['total'] / 100,
                    'status' => $eventObject['status'],
                ]
            );
        }
    }
}
