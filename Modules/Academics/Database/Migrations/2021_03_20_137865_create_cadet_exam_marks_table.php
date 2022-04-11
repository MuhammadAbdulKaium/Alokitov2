<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetExamMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_exam_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_year_id');
            $table->integer('semester_id');
            $table->integer('exam_id');
            $table->integer('subject_id');
            $table->integer('batch_id');
            $table->integer('section_id');
            $table->integer('student_id');
            $table->integer('subject_marks_id');
            $table->float('total_mark')->nullable();
            $table->float('total_conversion_mark')->nullable();
            $table->float('on_100')->nullable();
            $table->longText('breakdown_mark');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('campus_id');
            $table->integer('institute_id');
            $table->integer('exam_list_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadet_exam_marks');
    }
}
