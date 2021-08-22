<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpSalaryAssign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_emp_salary_assign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->references('id')->on('employee_informations')->onDelete('cascade');
            $table->integer('salary_structure_id')->references('id')->on('employee_informations')->onDelete('cascade');
            $table->float('salary_amount');
            $table->enum('salary_type',(['B','G']));
            $table->date('effective_date');
            $table->integer('company_id')->nullable();
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
        Schema::dropIfExists('pay_emp_salary_assign');
    }
}
