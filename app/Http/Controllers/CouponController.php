<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponCreateRequest;
use App\Http\Resources\CouponResource;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * CouponController constructor.
     * @param CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * @param CouponCreateRequest $request
     * @return CouponResource
     */
    public function create(CouponCreateRequest $request)
    {
        $coupon = $this->couponService->register($request->getEmail(), $request->getCode());

        return new CouponResource($coupon);
    }
}
