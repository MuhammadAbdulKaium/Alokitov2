<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeLeaveHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->unsigned()->nullable();
            $table->foreign('application_id')->references('id')->on('employee_leave_applications')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('leave_type')->unsigned()->nullable();
            $table->foreign('leave_type')->references('id')->on('employee_leave_types')->onUpdate('cascade')->onDelete('cascade');
            $table->date('approved_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('approved_leave_days')->nullable();
            $table->integer('employee')->unsigned()->nullable();
            $table->foreign('employee')->references('id')->on('employee_informations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('approved_by')->unsigned()->nullable();
            $table->foreign('approved_by')->references('id')->on('employee_informations')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('employee_leave_histories');
    }
}
