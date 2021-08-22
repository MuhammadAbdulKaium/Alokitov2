<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('not set');
            $table->string('details')->default('not set');
            $table->boolean('proportinate_on_joined_date')->dafault(0);
            $table->boolean('carray_forward')->default(0);
            $table->integer('percentage_of_cf')->unsigned()->default(0);
            $table->integer('max_cf_amount')->unsigned()->default(0);
            $table->integer('cf_availability_period')->unsigned()->default(0);
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
        Schema::dropIfExists('employee_leave_types');
    }
}
