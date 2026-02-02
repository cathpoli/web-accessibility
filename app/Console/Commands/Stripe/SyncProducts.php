<?php

namespace App\Console\Commands\Stripe;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class SyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all products from stripe';

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
        $lastPlan = '';

        while($hasMore) {
            $parameter = ['limit' => 10];

            if ($lastPlan != '') {
                $parameter['starting_after'] = $lastPlan;
            }

            $plansRaw = $stripe->products->all($parameter);
            $plans = $plansRaw['data'];

            foreach ($plans as $key => $plan) {
                $lastPlan = $plan->id;

                $product = Product::firstOrNew(['stripe_product' => $plan->id]);
                $product->name = $plan->name;
                $product->description = $plan->description;
                $product->is_active = $plan->active;

                $priceId = $plan->default_price;

                if ($priceId) {
                    // fetch product default price
                    $price = $stripe->prices->retrieve($priceId);

                    // set product price
                    $product->stripe_default_price = $priceId;
                    $product->price = $price->unit_amount / 100;
                    $product->interval = $price->recurring->interval;
                }

                $product->save();
            }

            $hasMore = $plansRaw['has_more'];
        }

        return Command::SUCCESS;
    }
}
