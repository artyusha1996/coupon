<?php


namespace App\Services;


use App\Events\CouponCreatedEvent;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Services\ThirdParty\CouponService as CouponThirdPartService;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class CouponService
{
    /**
     * @var CouponThirdPartService
     */
    private $couponService;

    const STATUS_PROCESSING = 'processing';
    const STATUS_NEW = 'new';
    const STATUS_USED = 'used';
    const STATUS_REJECTED = 'rejected';
    const RETRY_DATE_AFTER_CREATION_IN_MINUTES = 15;

    const STATUSES_CAN_BE_REGISTERED = [
        self::STATUS_NEW,
        self::STATUS_PROCESSING,
    ];

    /**
     * CouponService constructor.
     * @param CouponThirdPartService $couponService
     */
    public function __construct(CouponThirdPartService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * @param string $owner
     * @param string $code
     * @return mixed
     */
    public function register(string $owner, string $code)
    {
        $coupon = $this->firstByCode($code);
        if ($coupon) {
            throw new BadRequestException();
        }

        return $this->create($owner, $code);
    }

    /**
     * @param Coupon $coupon
     * @return Coupon
     */
    public function publish(Coupon $coupon)
    {
        if (!in_array($coupon->status, self::STATUSES_CAN_BE_REGISTERED)) {
            throw new BadRequestException();
        }
        $coupon->update([
            'status' => self::STATUS_PROCESSING,
        ]);

        try {
            $this->couponService->register($coupon->code);
            $coupon->update([
                'status' => self::STATUS_USED,
            ]);
        } catch (BadRequestException $exception) {
            $coupon->update([
                'status' => self::STATUS_REJECTED,
            ]);
        } catch (ServiceUnavailableHttpException $exception) {
            Log::info('The coupon service answers about server error: ' . $exception->getMessage());
        }

        return $coupon;
    }

    /**
     * @param $code
     * @return Coupon | null
     */
    public function firstByCode(string $code)
    {
        return Coupon::where('code', $code)->first();
    }

    /**
     * @param string $owner
     * @param string $code
     */
    public function create(string $owner, string $code)
    {
        $coupon =  Coupon::create([
            'code' => $code,
            'owner' => $owner,
            'status' => self::STATUS_NEW,
        ]);

        event(new CouponCreatedEvent($coupon));

        return $coupon;
    }

    /**
     * @return mixed
     */
    public function getCouponsWillBeRetried()
    {
        return Coupon::whereDate(
                'created_at',
                '<',
                Carbon::now()->subMinutes(self::RETRY_DATE_AFTER_CREATION_IN_MINUTES)
            )
            ->where('status', self::STATUS_NEW)
            ->get();
    }
}
