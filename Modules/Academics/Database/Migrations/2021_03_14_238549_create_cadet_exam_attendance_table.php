<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetExamAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_exam_attendance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_year_id');
            $table->integer('semester_id');
            $table->integer('exam_id');
            $table->integer('subject_id');
            $table->integer('criteria_id');
            $table->integer('batch_id');
            $table->integer('section_id');
            $table->longText('attendance');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('campus_id');
            $table->integer('institute_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadet_exam_attendance');
    }
}
