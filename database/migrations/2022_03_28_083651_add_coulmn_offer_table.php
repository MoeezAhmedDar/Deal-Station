<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoulmnOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->text('offer_desc_description')->nullable()->after('offer_description_arabic');
            $table->text('offer_desc_description_arabic')->nullable()->after('offer_desc_description');
            $table->bigInteger('offer_campaign')->default(0)->after('offer_code_generation');
            $table->string('offer_per_user')->nullable()->after('offer_campaign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('offer_desc_description');
            $table->dropColumn('offer_desc_description_arabic');
            $table->dropColumn('offer_campaign');
            $table->dropColumn('offer_per_user');
        });
    }
}
