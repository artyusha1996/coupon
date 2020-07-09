<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Coupon
 * @package App\Models
 * @property string $code
 * @property string $status
 * @property string $owner
 */
class Coupon extends Model
{
    public $fillable = [
        'code',
        'status',
        'owner',
    ];
}
