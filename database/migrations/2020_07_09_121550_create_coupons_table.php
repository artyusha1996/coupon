<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->enum('status', [
                \App\Services\CouponService::STATUS_NEW,
                \App\Services\CouponService::STATUS_PROCESSING,
                \App\Services\CouponService::STATUS_USED,
                \App\Services\CouponService::STATUS_REJECTED,
            ])->default(\App\Services\CouponService::STATUS_NEW);
            $table->string('owner')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
