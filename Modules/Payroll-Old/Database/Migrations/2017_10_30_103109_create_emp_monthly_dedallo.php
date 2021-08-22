<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpMonthlyDedallo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_emp_monthly_ded_allo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->references('id')->on('employee_informations')->onDelete('cascade');
            $table->enum('salary_type',(['B','G']));
            $table->integer('component_id')->references('id')->on('pay_salary_component')->onDelete('cascade');
            $table->float('amount');
            $table->enum('percent',(['','P']))->nullable();
            $table->date('approve_date')->nullable();
            $table->integer('effective_month');
            $table->integer('effective_year');
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
        Schema::dropIfExists('pay_emp_monthly_ded_allo');
    }
}
