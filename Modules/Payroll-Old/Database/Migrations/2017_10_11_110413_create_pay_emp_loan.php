<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayEmpLoan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_emp_loan', function (Blueprint $table) {
            $table->increments('id');
            $table->date('approve_date');
            $table->integer('employee_id')->references('id')->on('employee_informations')->onDelete('cascade');
            $table->integer('loan_type_id')->references('id')->on('pay_salary_component')->onDelete('cascade');
            $table->float('loan_amount');
            $table->enum('loan_fee_type',(['','A','P']))->nullable();
            $table->integer('installment_no');
            $table->float('loan_fee_amount')->nullable();
            $table->float('installment_amount');
            $table->date('deduction_date');
            $table->integer('company_id')->nullable();
            $table->integer('brunch_id')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_emp_loan');
    }
}
