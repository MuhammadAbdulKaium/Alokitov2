<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicesStudentAttendancesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academices_student_attendances_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_attendace_id')->unsigned()->default(0);
            $table->integer('class_id')->unsigned()->default(0);
            $table->integer('section_id')->unsigned()->default(0);
            $table->integer('subject_id')->unsigned()->nullable();
            $table->integer('session_id')->unsigned()->default(0);
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
        Schema::dropIfExists('academices_student_attendances_details');
    }
}
