<?php

namespace App\Events;

use App\Models\Coupon;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CouponCreatedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var Coupon
     */
    public $coupon;

    /**
     * CouponCreatedEvent constructor.
     * @param Coupon $coupon
     */
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }
}
