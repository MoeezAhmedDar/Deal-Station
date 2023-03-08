<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OfferCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer')->unsigned()->index();
            $table->bigInteger('category')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('offer_categories', function ($table) {
            $table->foreign('offer')->references('id')->on('offers')->onDelete('restrict');
            $table->foreign('category')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_categories');
    }
}
