<?php

namespace App\Console\Commands\Stripe;

use App\Models\Domain;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Stripe\StripeClient;

class SyncSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:subscription {stripe_subscription_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync given subscription/domain from stripe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscriptionId = $this->argument('stripe_subscription_id');
        $subscription = Subscription::where('stripe_id', $subscriptionId)->first();

        if (! $subscription) {
            $this->error("Subscription with id {$subscriptionId} does not exists.");
            return Command::FAILURE;
        }

        $secretKey = config('cashier.secret');
        $stripe = new StripeClient($secretKey);

        $stripeSubscription = $stripe->subscriptions->retrieve($subscription->stripe_id);

        $domainId = $stripeSubscription->metadata->domain_id;
        $domainName = $stripeSubscription->metadata->domain;

        $domain = Domain::find($domainId);

        if (! $domain) {
            $this->error("Domain with id {$domainId} does not exists.");
            return Command::FAILURE;
        }

        $nextPaymentAt = Carbon::createFromTimestamp($stripeSubscription->current_period_end);

        $subscription->update([
            'stripe_status' => $stripeSubscription->status,
        ]);

        $domain->update([
            'subscription_id' => $subscription->id,
            'next_payment_at' => $nextPaymentAt,
        ]);

        return Command::SUCCESS;
    }
}
