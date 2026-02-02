<?php

namespace App\Console\Commands\Stripe;

use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Stripe\StripeClient;

class SyncInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all invoices from stripe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $secretKey = config('cashier.secret');
        $stripe = new StripeClient($secretKey);

        $hasMore = true;
        $lastInvoice = '';

        while($hasMore) {
            $parameter = ['limit' => 10];

            if ($lastInvoice != '') {
                $parameter['starting_after'] = $lastInvoice;
            }

            $invoicesRaw = $stripe->invoices->all($parameter);

            $invoices = $invoicesRaw['data'];

            foreach ($invoices as $key => $stripeInvoice) {
                $lastInvoice = $stripeInvoice->id;

                $user = User::where('stripe_id', $stripeInvoice->customer)->first();

                if (! $user) return;

                /** save invoice basic info */
                $invoice = Invoice::firstOrNew(['stripe_invoice' => $stripeInvoice->id]);
                $invoice->user_id = $user->id ?? null;
                $invoice->number = $stripeInvoice->number;
                $invoice->stripe_customer = $stripeInvoice->customer;

                if ($stripeInvoice->subscription) {
                    $subscription = Subscription::where('stripe_id', $stripeInvoice->subscription)->first();

                    if ($subscription) {
                        $domain = Domain::where('subscription_id', $subscription->id)->first();
                        $invoice->stripe_subscription = $stripeInvoice->subscription;
                        if ($domain) {
                            $invoice->domain_id = $domain->id;
                        }
                    }
                }

                if ($stripeInvoice->charge) {
                    $payment = Payment::where('stripe_charge', $stripeInvoice->charge)->first();
                    $invoice->stripe_charge = $stripeInvoice->charge;
                    if ($payment) {
                        $invoice->payment_id = $payment->id;
                    }
                }

                $lineItems = $stripeInvoice['lines']['data'];

                $invoice->period_start = Carbon::createFromTimestamp($lineItems[0]['period']['start']);
                $invoice->period_end = Carbon::createFromTimestamp($lineItems[0]['period']['end']);
                $invoice->subtotal = $stripeInvoice->subtotal / 100;
                $invoice->total = $stripeInvoice->total / 100;
                $invoice->status = $stripeInvoice->status;
                $invoice->save();
            }

            $hasMore = $invoicesRaw['has_more'];
        }

        return Command::SUCCESS;
    }
}
