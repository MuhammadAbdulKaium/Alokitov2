<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionManagementTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_management_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('institute_billing_info_id')->unsigned();
            $table->decimal('old_dues', 8, 2)->nullable();
            $table->decimal('monthly_total_charge', 8, 2)->nullable();
            $table->decimal('paid_amount', 8, 2)->nullable();
            $table->decimal('new_dues', 8, 2)->nullable();
            $table->dateTime('paid_on')->nullable();
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
        Schema::dropIfExists('subscription_management_transactions');
    }
}
