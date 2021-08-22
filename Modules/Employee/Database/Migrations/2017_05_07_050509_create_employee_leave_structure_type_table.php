<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveStructureTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_structure_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('structure_id')->unsigned()->default(0);
            $table->foreign('structure_id')->references('id')->on('employee_leave_structures')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('type_id')->unsigned()->default(0);
            $table->foreign('type_id')->references('id')->on('employee_leave_types')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('leave_days')->unsigned()->default(0);
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
        Schema::dropIfExists('employee_leave_structure_type');
    }
}
