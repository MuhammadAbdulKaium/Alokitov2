<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsSubsidiaryCalculationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_subsidiary_calculation', function (Blueprint $table) {
            $table->id();
            $table->integer('particular_sub_ledger_id')->nullable();
            $table->date('trans_date');
            $table->dateTime('trans_date_time');
            $table->integer('sub_ledger');
            $table->enum('increase_by', ['debit', 'credit']);
            $table->double('debit_amount',16,2);
            $table->double('credit_amount',16,2);
            $table->tinyInteger('transaction_type')->default(1);
            $table->integer('transaction_id');
            $table->integer('tran_details_id')->nullable();
            $table->tinyInteger('ac_payee')->nullable();
            $table->string('pay_to')->nullable();
            $table->string('cheque_no',150)->nullable();
            $table->date('cheque_date')->nullable();
            $table->date('draw_on')->nullable();
            $table->tinyInteger('approval_level')->default(1);
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=apprroved,2=reject');
            $table->string('remarks')->nullable();
            $table->enum('tras_ledger_type', ['debit', 'credit'])->nullable();
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
        Schema::dropIfExists('accounts_subsidiary_calculation');
    }
}
