<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeekOffDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week_off_days', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->integer('dept_id')->unsigned()->nullable();
            $table->foreign('dept_id')->references('id')->on('employee_departments')->onDelete('cascade');
            $table->integer('academic_year')->unsigned()->nullable();
            $table->foreign('academic_year')->references('id')->on('academics_year')->onDelete('cascade');
            $table->integer('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('week_off_days');
    }
}
