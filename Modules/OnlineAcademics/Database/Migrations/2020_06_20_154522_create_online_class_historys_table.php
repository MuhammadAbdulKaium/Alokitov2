<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineClassHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_class_historys', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('institute_id')->nullable();
			$table->integer('campus_id')->nullable();
			$table->integer('academic_year_id')->nullable();
			$table->string('academic_year')->nullable();
			$table->integer('academic_level_id')->nullable();
			$table->string('academic_level')->nullable();
			$table->integer('academic_class_id')->nullable();
			$table->string('academic_class')->nullable();
			$table->integer('academic_section_id')->nullable();
			$table->string('academic_section')->nullable();
			$table->integer('class_subject_id')->nullable();
			$table->string('class_subject')->nullable();
			$table->integer('class_teacher_id')->nullable();
			$table->string('class_teacher_name')->nullable();
			$table->integer('student_present')->nullable();
			$table->integer('student_absent')->nullable();
			$table->integer('student_leave')->nullable();
			$table->string('teacher_remarks')->nullable();
			$table->string('conduct_time')->nullable();
			$table->string('teacher_class_status')->nullable();
			$table->string('class_opening_date')->nullable();
			$table->string('class_opening_day')->nullable();
			$table->integer('class_total_student')->nullable();
			$table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_class_historys');
    }
}
