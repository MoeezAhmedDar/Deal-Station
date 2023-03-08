<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('merchant_id');
            $table->string('branch_uniid')->unique();
            $table->string('branch_name');
            $table->string('branch_name_arabic');
            $table->text('branch_building_address');
            $table->string('branch_str_address');
            $table->string('branch_com_address');
            $table->string('branch_latitude');
            $table->string('branch_longitude');
            $table->bigInteger('branch_city');
            $table->string('branch_phone');
            $table->text('branch_image');
            $table->integer('branch_status');
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
        Schema::dropIfExists('branches');
    }
}
