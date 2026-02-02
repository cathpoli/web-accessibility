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
use App\Services\Helper\Helper;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpgradeNoticeMail;
use App\Models\Product;

class SendUpgradeNoticeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $subscriptions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->subscriptions as $subscription) {
            $domain = Domain::where('subscription_id', $subscription->id)->first();
            if ($domain) {
                $optimalPlan = Helper::getOptimalPlan(explode('-', $domain->product->slug)[1]);
                $product = Product::where('slug', 'like', '%' . $optimalPlan .'%')->get();

                $planPage = Helper::getPlanPages()[$domain->product->slug];
                $currentPageCount = Helper::getSitePageCount($domain->name);
                $percentage = ($currentPageCount / $planPage['to']) * 100;
                if ($percentage >= 80 && $optimalPlan !== null) {
                    $subject = 'AceADA Upgrade Notice';
                    Mail::to($domain->user->email)->send(new UpgradeNoticeMail($subject, $domain->user, $domain, $product));
                }
            }
        }
    }
}
