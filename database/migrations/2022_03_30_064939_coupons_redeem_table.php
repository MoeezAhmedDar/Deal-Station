<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CouponsRedeemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_redeems', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer_id')->unsigned()->index();
            $table->bigInteger('coupons_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('coupon_redeems', function ($table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('restrict');
            $table->foreign('coupons_id')->references('id')->on('coupons')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_redeems');
    }
}
