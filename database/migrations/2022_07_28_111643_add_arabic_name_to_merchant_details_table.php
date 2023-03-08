<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArabicNameToMerchantDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_details', function (Blueprint $table) {
            $table->string('arabic_business_owner')->after('merchant_brand_arabic')->nullable();
            $table->string('arabic_contact_person_name')->after('arabic_business_owner')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_details', function (Blueprint $table) {
            $table->dropColumn('arabic_business_owner');
            $table->dropColumn('arabic_contact_person_name');
        });
    }
}
