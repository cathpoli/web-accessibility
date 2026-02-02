<?php

namespace App\Console\Commands\Stripe;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Console\Command;
use Stripe\StripeClient;

class SyncCharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charges:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all charges from stripe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $secretKey = config('cashier.secret');
        $stripe = new StripeClient($secretKey);

        $chargesRaw = $stripe->charges->all();
        $charges = $chargesRaw['data'];

        foreach ($charges as $key => $charge) {
            $user = User::where('stripe_id', $charge->customer)->first();

            if (! $user) return;

            $payment = Payment::firstOrNew(['stripe_charge' => $charge->id]);
            $payment->user_id = $user->id;
            $payment->stripe_invoice = $charge->invoice;
            $payment->amount = $charge->amount / 100;
            $payment->balance_transaction = $charge->balance_transaction;
            $payment->is_paid = $charge->paid;
            $payment->status = $charge->status;
            $payment->save();
        }

        return Command::SUCCESS;
    }
}
