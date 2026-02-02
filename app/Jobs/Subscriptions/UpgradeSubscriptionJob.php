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
use App\Mail\AutomaticUpgradeNoticeMail;
use App\Models\Product;
use Exception;
use Illuminate\Validation\ValidationException;
class UpgradeSubscriptionJob implements ShouldQueue
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
        try {
            foreach ($this->subscriptions as $subscription) {
                $domain = Domain::where('subscription_id', $subscription->id)->first();
                if ($domain) {
                    $planPage = Helper::getPlanPages()[$domain->product->slug];
                    $currentPageCount = Helper::getSitePageCount($domain->name);
                    if ($currentPageCount > $planPage['to']) {
                        $optimalPlan = Helper::getOptimalPlan(explode('-', $domain->product->slug)[1]);
                        
    
                        if ($optimalPlan !== null) {
                            $product = Product::where('slug', 'like', '%' . $optimalPlan .'%')
                                        ->where('interval', $domain->product->interval)->first();
    
                            $user = $domain->user;
    
                            /** @return \Laravel\Cashier\PaymentMethod */
                            $paymentMethod = $user->defaultPaymentMethod();
    
                            /** @return \Stripe\PaymentMethod */
                            $stripePaymentMethod = $paymentMethod->asStripePaymentMethod();
    
                            // make sure user has setup payment method
                            if (! $paymentMethod || $user->stripe_id === null) {
                                throw new Exception('Please add payment method first.');
                            }
    
                            /** @return \Laravel\Cashier\SubscriptionBuilder */
                            $builder = $user->newSubscription('default', $product->stripe_default_price);
    
                            /** @return \Laravel\Cashier\Subscription */
                            $subscription = $builder->create($stripePaymentMethod, [], [
                                        'default_payment_method' => $stripePaymentMethod,
                                        'metadata' => [
                                            'domain_id' => $domain->id,
                                            'domain' => $domain->name,
                                        ]
                                    ]);
    
                            /**
                             * Note: We need to attach the subscription to the domain. but in some cases that invoice payment fails.
                             *          So we will handle that in the CreatedListener on the stripe webhook
                            */
                            $domain->page_count = $currentPageCount;
                            $domain->product_id = $product->id;
                            $domain->save();
    
    
                            $subject = 'AceADA Automatic Upgrade Notice';
                            Mail::to($user->email)->send(new AutomaticUpgradeNoticeMail($subject, $user, $domain, $product));
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            throw ValidationException::withMessages(['message' => 'Something went wrong.']);
        }
    }
}