<?php

namespace App\Jobs\Subscriptions;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteTestModeSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $domainId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $domainId)
    {
        $this->domainId = $domainId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $domain = Domain::find($this->domainId);

        if ($domain) {
            $subscription = $domain->subscription;
            $domain->delete();

            Log::info("Test mode subscription deleted: {$subscription->stripe_id} - {$domain->name}");
        }
    }
}
