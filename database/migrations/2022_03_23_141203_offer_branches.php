<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OfferBranches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer')->unsigned()->index();
            $table->bigInteger('branch')->unsigned()->index();
            $table->double('coupons')->nullable();
            $table->timestamps();
        });

        Schema::table('offer_branches', function ($table) {
            $table->foreign('offer')->references('id')->on('offers')->onDelete('restrict');
            $table->foreign('branch')->references('id')->on('branches')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_branches');
    }
}
