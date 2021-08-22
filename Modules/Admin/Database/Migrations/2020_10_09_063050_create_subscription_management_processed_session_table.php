<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionManagementProcessedSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_management_processed_session', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('subscription_management_transactions_id')->unsigned();
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->decimal('accepted_amount', 8, 2)->nullable();
            $table->decimal('total_sms_price', 8, 2)->nullable();
            $table->decimal('accepted_sms_price', 8, 2)->nullable();
            $table->decimal('old_dues', 8, 2)->nullable();
            $table->decimal('monthly_total_charge', 8, 2)->nullable();
            $table->decimal('paid_amount', 8, 2)->nullable();
            $table->decimal('new_dues', 8, 2)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('sms', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('invoice', 100)->nullable();
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
        Schema::dropIfExists('subscription_management_processed_session');
    }
}
