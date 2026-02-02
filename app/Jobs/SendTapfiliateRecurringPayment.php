<?php

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTapfiliateRecurringPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $customerId;

    private $invoiceId;

    private $amount;

    private $commissionType = 'recurring';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $customerId, $invoiceId, $amount)
    {
        $this->customerId = $customerId;
        $this->invoiceId = $invoiceId;
        $this->amount = $amount;

        // check if this is user's first conversion, set commission_type to standard if so
        $userConversionsCount = Invoice::where('user_id', $userId)->where('status', 'paid')->count();
        if ($userConversionsCount === 1) $this->commissionType = 'standard';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** Reference: https://tapfiliate.com/docs/rest/#conversions-conversions-collection-post */
        try {
            $response = Http::withHeaders(['Api-Key' => config('tapfiliate.api_key')])
                ->post(config('tapfiliate.endpoint') . '/conversions/', [
                    'customer_id' => $this->customerId,
                    'external_id' => $this->invoiceId,
                    'amount' => $this->amount,
                    'commission_type' => $this->commissionType,
                ]);

            Log::info($response);
        } catch (\Throwable $th) {
            Log::error("Tapfiliate recurring paymen error [customer_id] {$this->customerId} [external_id] {$this->invoiceId} : " . $th->getMessage());
        }
    }
}
