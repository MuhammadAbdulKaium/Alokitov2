<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetExamSeatPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_exam_seat_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_year_id');
            $table->integer('semester_id');
            $table->integer('exam_id');
            $table->longText('employee_ids');
            $table->date('date');
            $table->time('from_time');
            $table->time('to_time');
            $table->longText('physical_room_ids');
            $table->longText('batch_ids');
            $table->longText('section_ids');
            $table->longText('batch_with_subjects');
            $table->longText('seat_plan');
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
        Schema::dropIfExists('cadet_exam_seat_plans');
    }
}
