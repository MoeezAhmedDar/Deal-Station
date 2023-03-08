<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CouponsTables extends Migration
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
            $table->bigInteger('offer_id')->unsigned()->index();
            $table->string('coupon_code')->unique();
            $table->bigInteger('coupon_per_user');
            $table->bigInteger('coupon_usage_duration');
            $table->bigInteger('coupon_status');
            $table->timestamps();
        });

        Schema::table('coupons', function ($table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('restrict');
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
