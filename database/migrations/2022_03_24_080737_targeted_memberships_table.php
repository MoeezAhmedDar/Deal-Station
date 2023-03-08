<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TargetedMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targeted_memberships', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer')->unsigned()->index();
            $table->bigInteger('plan')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('targeted_memberships', function ($table) {
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
        Schema::dropIfExists('targeted_memberships');
    }
}
