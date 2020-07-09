<?php

namespace App\Listeners;

use App\Events\CouponStatusChangedEvent;
use Illuminate\Support\Facades\Log;

class CouponStatusChangedListener
{
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
     * @param CouponStatusChangedEvent $event
     */
    public function handle(CouponStatusChangedEvent $event)
    {
        $code = $event->coupon->code;
        Log::info("Coupon [$code] status was changed to:" . $event->coupon->status);
    }
}
