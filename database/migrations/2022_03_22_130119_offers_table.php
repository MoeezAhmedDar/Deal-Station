<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('offer_uniid')->unique();
            $table->string('offer_name');
            $table->string('offer_name_arabic');
            $table->text('offer_description');
            $table->text('offer_description_arabic');
            $table->text('offer_image_link');
            $table->double('offer_discount');
            $table->date('offer_to');
            $table->date('offer_from');
            $table->double('offer_coupons');
            $table->string('offer_coupon_type');
            $table->string('offer_type');
            $table->string('offer_code_generation');
            $table->bigInteger('offer_status')->default(1);
            $table->text('offer_comments');
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
        Schema::dropIfExists('offers');
    }
}
