<?php

namespace App\Console\Commands\Stripe;

use Illuminate\Console\Command;

class SyncModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all models from stripe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** call all stripe sync commands */
        $this->call('coupons:sync');
        $this->call('products:sync');
        $this->call('invoices:sync');
        $this->call('charges:sync');
        $this->call('payment-methods:sync');

        return Command::SUCCESS;
    }
}
