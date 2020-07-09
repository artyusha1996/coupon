<?php

namespace App\Providers;

use App\Events\CouponCreatedEvent;
use App\Events\CouponStatusChangedEvent;
use App\Listeners\CouponPublishListener;
use App\Listeners\CouponStatusChangedListener;
use App\Models\Coupon;
use App\Observer\CouponObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CouponCreatedEvent::class => [
            CouponPublishListener::class,
        ],
        CouponStatusChangedEvent::class => [
            CouponStatusChangedListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Coupon::observe(CouponObserver::class);
    }
}
