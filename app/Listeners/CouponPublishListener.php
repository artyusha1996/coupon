<?php

namespace App\Listeners;

use App\Events\CouponCreatedEvent;
use App\Services\CouponService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CouponPublishListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * @var CouponService
     */
    private $couponsService;
    /**
     * CouponPublishListener constructor.
     * @param CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponsService = $couponService;
    }

    /**
     * @param CouponCreatedEvent $event
     * @return \App\Models\Coupon
     */
    public function handle(CouponCreatedEvent $event)
    {
        return $this->couponsService->publish($event->coupon);
    }

    /**
     * @param CouponCreatedEvent $event
     * @param null $exception
     * @return bool
     */
    public function fail(CouponCreatedEvent $event, $exception = null)
    {
        return $event->coupon->update([
            'status' => CouponService::STATUS_REJECTED,
        ]);
    }
}
