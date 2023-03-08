<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsRedeemCoupons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_redeems', function (Blueprint $table) {
            $table->double('discount')->default(0)->after('user_id');
            $table->double('price')->default(0)->after('discount');
            $table->double('discount_price')->default(0)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_redeems', function (Blueprint $table) {
            $table->dropColumn('discount');
            $table->dropColumn('price');
            $table->dropColumn('discount_price');
        });
    }
}
