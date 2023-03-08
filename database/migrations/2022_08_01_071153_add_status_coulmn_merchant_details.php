<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusCoulmnMerchantDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_details', function (Blueprint $table) {
            $table->bigInteger('merchant_status')->default(1)->after('merchant_tax_letter');
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
            $table->dropColumn('merchant_status');
        });
    }
}
