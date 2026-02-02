<?php

namespace App\Console\Commands\Stripe;

use App\Models\Card;
use App\Models\User;
use Illuminate\Console\Command;

class SyncPaymentMethods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment-methods:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all payment methods from stripe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clients = User::clients()->whereNotNull('stripe_id')->get();

        foreach ($clients as $key => $user) {
            /** @return \Laravel\Cashier\PaymentMethod[] */
            $paymentMethods = $user->paymentMethods();

            foreach ($paymentMethods as $key => $paymentMethod) {
                /** @return \Stripe\PaymentMethod */
                // $stripePaymentMethod = $paymentMethod->asStripePaymentMethod();

                /** save invoice basic info */
                $card = Card::firstOrNew(['stripe_payment_method' => $paymentMethod->id]);
                $card->user_id = $user->id;
                $card->brand = $paymentMethod->card->brand;
                $card->last4 = $paymentMethod->card->last4;
                $card->exp_month = $paymentMethod->card->exp_month;
                $card->exp_year = $paymentMethod->card->exp_year;
                $card->billing_name = $paymentMethod->billing_details->name;
                $card->billing_email = $paymentMethod->billing_details->email;
                $card->billing_phone = $paymentMethod->billing_details->phone;
                $card->save();
            }
        }

        return Command::SUCCESS;
    }
}
