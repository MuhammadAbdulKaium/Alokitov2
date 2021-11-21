<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->integer('acc_transaction_id');
            $table->integer('dr_sub_ledger');
            $table->integer('cr_sub_ledger');
            $table->double('amount',16,2);
            $table->tinyInteger('approval_level')->default(1)->comment('current approval layer');
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=apprroved,2=reject');
            $table->string('remarks')->nullable();
            $table->tinyInteger('dr_ac_payee')->default(1)->comment('1=yes,0=no');
            $table->string('dr_pay_to')->nullable();
            $table->integer('dr_cheque_no')->nullable();
            $table->date('dr_cheque_date')->nullable();
            $table->date('dr_draw_on');
            $table->tinyInteger('cr_ac_payee');
            $table->string('cr_pay_to')->nullable();
            $table->integer('cr_cheque_no')->nullable();
            $table->date('cr_cheque_date')->nullable();
            $table->date('cr_draw_on')->nullable();
            $table->integer('institute_id');
            $table->integer('campus_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('accounts_transaction_details');
    }
}
