<?php

namespace App\Console\Commands\Subscription;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Subscriptions\SendUpgradeNoticeJob;

class UpgradeNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:upgrade-notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for upgrade notice if subscription reached 80% plan coverage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $subscriptions = Subscription::where('stripe_status', 'active');

        $subscriptions->orderBy('id')->chunk(10, function ($items) {
            SendUpgradeNoticeJob::dispatch($items)->delay(now()->addMinute());
        });

        return Command::SUCCESS;
    }
}
