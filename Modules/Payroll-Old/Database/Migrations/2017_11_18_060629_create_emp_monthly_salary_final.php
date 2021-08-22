<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpMonthlySalaryFinal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_emp_monthly_salary', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->references('id')->on('employee_informations')->onDelete('cascade');
            $table->integer('assign_id')->references('id')->on('pay_emp_salary_assign')->onDelete('cascade');
            $table->integer('component_id')->references('id')->on('pay_salary_component')->onDelete('cascade');
            $table->float('amount');
            $table->date('effective_date');
            $table->integer('company_id');
            $table->integer('brunch_id')->nullable();
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
        Schema::dropIfExists('pay_emp_monthly_salary');
    }
}
