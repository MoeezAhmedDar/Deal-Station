<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('app_name_arabic')->nullable();
            $table->string('app_phone');
            $table->string('app_email');
            $table->text('app_building_address');
            $table->string('app_str_address');
            $table->string('app_com_address');

            $table->text('app_facebook')->nullable();
            $table->text('app_insta')->nullable();
            $table->text('app_twitter')->nullable();
            $table->text('app_pinterest')->nullable();

            $table->text('app_logo_ltr')->nullable();
            $table->text('app_logo_rtl')->nullable();;

            $table->longText('app_privacy');
            $table->longText('app_privacy_arabic')->nullable();
            $table->longText('app_about');
            $table->longText('app_about_arabic')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
