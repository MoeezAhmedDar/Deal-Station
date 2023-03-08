<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('advertisement_uniid')->unique();
            $table->string('advertisement_name');
            $table->string('advertisement_name_arabic')->nullable();
            $table->bigInteger('advertisement_type')->default(0);
            $table->text('advertisement_image');
            $table->integer('advertisement_status')->default(1);
            $table->text('advertisement_text')->nullable();
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
        Schema::dropIfExists('advertisements');
    }
}
