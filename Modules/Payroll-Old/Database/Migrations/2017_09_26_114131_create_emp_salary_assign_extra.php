<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpSalaryAssignExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_emp_salary_assign_extra', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assign_id')->unsigned();
            $table->foreign('assign_id')->references('id')->on('pay_emp_salary_assign')->onDelete('cascade');
            $table->integer('component_id')->unsigned();
            $table->foreign('component_id')->references('id')->on('pay_salary_component')->onDelete('cascade');
            $table->float('amount');
            $table->enum('percent',(['','P']))->nullable();
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
        Schema::dropIfExists('pay_emp_salary_assign_extra');
    }
}
