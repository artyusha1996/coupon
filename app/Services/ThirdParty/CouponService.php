<?php


namespace App\Services\ThirdParty;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * Class CouponService
 * @package App\Services\ThirdParty
 * @method
 */
class CouponService
{
    public function register(string $param)
    {
        $codes = ['200 Success',  '400 Bad Request',  '500 Internal Server Error'];
        $code = $codes[array_rand($codes)];
        sleep(rand(1,10));
        if ($code === $codes[1]) {
            throw new BadRequestException();
        } elseif ($code === $codes[2]) {
            throw new ServiceUnavailableHttpException();
        }
    }
}
