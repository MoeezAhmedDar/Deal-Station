<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchRedeemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_redeems', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer_id')->unsigned()->index();
            $table->bigInteger('branch_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('branch_redeems', function ($table) {
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('restrict');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_redeems');
    }
}
