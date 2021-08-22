<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(0);
            $table->integer('leave_type')->unsigned()->nullable();
            $table->foreign('leave_type')->references('id')->on('employee_leave_types')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('leave_days')->nullable();
            $table->string('leave_reason', 500)->nullable();
            $table->string('remarks', 500)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('applied_date')->nullable();
            $table->integer('employee')->unsigned()->nullable();
            $table->foreign('employee')->references('id')->on('employee_informations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('designation')->unsigned()->nullable();
            $table->foreign('designation')->references('id')->on('employee_designations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('department')->unsigned()->nullable();
            $table->foreign('department')->references('id')->on('employee_departments')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('employee_leave_applications');
    }
}
