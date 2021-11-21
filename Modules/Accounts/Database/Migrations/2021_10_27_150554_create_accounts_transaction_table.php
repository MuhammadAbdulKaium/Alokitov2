<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_transaction', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no', 100);
            $table->integer('voucher_int_no');
            $table->integer('voucher_config_id')->nullable();
            $table->string('voucher_from',50)->default('Accounts')->nullable();
            $table->date('trans_date');
            $table->timestamp('trans_date_time')->useCurrent();
            $table->double('amount',16,2);
            $table->enum('voucher_type', ['1', '2', '3', '4'])->comment('1=Payment voucher, 2=Receive voucher, 3=Journal voucher, 4=Contra voucher');
            $table->string('payment_receive_by',20)->nullable()->comment('Use for payment voucher, Payment by bank = payment_bank, Payment by cash = payment_cash, For other voucher it should be null');
            $table->string('manual_ref_no', 150)->nullable();
            $table->tinyInteger('approval_level')->default(1)->comment('current approval layer');
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=apprroved,2=reject');
            $table->string('remarks')->nullable();
            $table->integer('institute_id');
            $table->integer('campus_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('accounts_transaction');
    }
}
