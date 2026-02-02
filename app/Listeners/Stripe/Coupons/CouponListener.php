<?php

namespace App\Listeners\Stripe\Coupons;

use App\Models\Coupon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CouponListener
{
    /** Events: https://stripe.com/docs/api/events/types#event_types-invoice.created */
    private const INVOICE_EVENTS = [
        'coupon.created',
        'coupon.updated',
    ];

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        /** stripe events: https://stripe.com/docs/api/events/types */
        $eventType = $event->payload['type'];
        $eventObject = $event->payload['data']['object'];

        /** created or updated*/
        if (in_array($eventType, self::INVOICE_EVENTS)) {
            $coupon = Coupon::updateOrCreate(
                ['code' => $eventObject['id']],
                [
                    'name' => $eventObject['name'],
                    'percent_off' => $eventObject['percent_off'] ?? 0,
                    'amount_off' => $eventObject['amount_off'] ?? 0,
                    'duration' => $eventObject['duration'],
                    'times_redeemed' => $eventObject['times_redeemed'],
                ]
            );
        }

        /** deleted */
        if ($eventType === 'coupon.deleted') {
            $coupon = Coupon::where(['code' => $eventObject['id']])->delete();
        }
    }
}
