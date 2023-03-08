<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemebshipCoulmns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->text('subscription_description')->nullable()->after('subscription_duration');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->text('plan_description')->nullable()->after('plan_terms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('subscription_description');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('plan_description');
        });
    }
}
