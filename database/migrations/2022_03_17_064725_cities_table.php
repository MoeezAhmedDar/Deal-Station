<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('city_uniid')->unique();
            $table->string('city_name');
            $table->string('city_name_arabic');
            $table->string('city_zip')->nullable();
            $table->string('city_latitude')->nullable();
            $table->string('city_longitude')->nullable();
            $table->string('city_country')->nullable();
            $table->integer('city_status')->default(1);
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
        Schema::dropIfExists('cities');
    }
}
