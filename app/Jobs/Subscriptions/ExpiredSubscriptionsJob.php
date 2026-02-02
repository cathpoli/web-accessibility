<?php

namespace App\Jobs\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpiredSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::findOrFail($this->userId);

        $subscription = Subscription::where('user_id', $user->id)
                                        ->where('subscription_plan_id', $user->subscription_plan_id)
                                        ->latest()
                                        ->first();

        $expiration = Carbon::parse($subscription->expiration_date);

        if ($expiration->isToday() || $expiration->isPast()) {
            $user->subscription_plan_id = null;
            $user->save();
        }
    }
}
