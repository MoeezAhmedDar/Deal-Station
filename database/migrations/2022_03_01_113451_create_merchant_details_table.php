<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('merchant_id');
            $table->string('merchant_uniid')->unique();
            $table->string('merchant_brand');
            $table->string('merchant_brand_arabic');
            $table->string('merchant_iban');
            $table->string('merchant_gov_id');
            $table->text('merchant_website');
            $table->string('merchant_number');
            $table->string('business_owner');
            $table->string('merchant_contact_person');
            $table->string('merchant_contact_number');
            $table->text('merchant_building_address');
            $table->string('merchant_str_address');
            $table->string('merchant_com_address');
            $table->text('merchant_commercial_activity');
            $table->string('merchant_tax_number');
            $table->text('merchant_logo');
            $table->text('merchant_gov_letter');
            $table->text('merchant_tax_letter');
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
        Schema::dropIfExists('merchant_details');
    }
}
