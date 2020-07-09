<?php


namespace App\Observer;


use App\Events\CouponStatusChangedEvent;
use App\Models\Coupon;

class CouponObserver
{
    public function updating(Coupon $coupon)
    {
        if ($coupon->isDirty('status')) {
            event(new CouponStatusChangedEvent($coupon));
        }
    }
}
