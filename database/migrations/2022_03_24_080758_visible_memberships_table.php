<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VisibleMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visible_memberships', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer')->unsigned()->index();
            $table->bigInteger('plan')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('visible_memberships', function ($table) {
            $table->foreign('offer')->references('id')->on('offers')->onDelete('restrict');
            $table->foreign('plan')->references('id')->on('plans')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visible_memberships');
    }
}
