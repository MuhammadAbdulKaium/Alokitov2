<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveEntitlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_entitlements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category')->nullable();
            $table->integer('structure')->nullable();
            $table->foreign('structure')->references('id')->on('employee_leave_structures')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('employee')->unsigned()->nullable();
            $table->foreign('employee')->references('id')->on('employee_informations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('designation')->unsigned()->nullable();
            $table->foreign('designation')->references('id')->on('employee_designations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('department')->unsigned()->nullable();
            $table->foreign('department')->references('id')->on('employee_departments')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('is_custom')->nullable()->default(0);
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
        Schema::dropIfExists('employee_leave_entitlements');
    }
}
