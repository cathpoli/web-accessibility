<?php
namespace App\Console\Commands\Subscription;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Subscriptions\UpgradeSubscriptionJob;
class UpgradeAutomatic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:upgrade-automatic';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The subscription will be upgraded automatic when the page count is exceeded on the current subscription';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscriptions = Subscription::where('stripe_status', 'active');
        $subscriptions->orderBy('id')->chunk(10, function ($items) {
            UpgradeSubscriptionJob::dispatch($items)->delay(now()->addMinute());
        });
        return Command::SUCCESS;
    }
}