<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoulmnsSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dateTime('user_subscriptions_expiry')->nullable()->after('user_id');
            $table->enum('user_subscriptions_payment_status', ['Pending', 'Canceled', 'Hold', 'Paid'])->default('Pending')->after('user_subscriptions_expiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropIfExists('user_subscriptions_expiry');
            $table->dropIfExists('user_subscriptions_payment_status');
        });
    }
}
