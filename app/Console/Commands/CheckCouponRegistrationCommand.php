<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use App\Services\CouponService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckCouponRegistrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register coupons which noy processed about 1 hour after creation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param CouponService $couponService
     */
    public function handle(CouponService $couponService)
    {
        Coupon::whereDate(
                'created_at',
                '<',
                Carbon::now()->subMinutes(CouponService::RETRY_DATE_AFTER_CREATION_IN_MINUTES)
            )
            ->where('status', CouponService::STATUS_NEW)
            ->chunk(100, function ($coupons) use ($couponService) {
                foreach ($coupons as $coupon) {
                    $couponService->publish($coupon);
                }
            });
    }
}
