<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeOtHour extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_ot_hour', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->references('id')->on('employee_informations')->onDelete('cascade');
            $table->float('ot_hours');
            $table->date('approve_date');
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
        Schema::dropIfExists('employee_ot_hour');
    }
}
