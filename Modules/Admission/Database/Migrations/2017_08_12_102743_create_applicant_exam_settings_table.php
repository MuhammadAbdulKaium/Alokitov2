<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantExamSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_exam_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_fees')->nullbale();
            $table->integer('merit_list_std_no')->nullbale();
            $table->integer('waiting_list_std_no')->nullbale();
            $table->integer('exam_marks')->nullbale();
            $table->integer('exam_passing_marks')->nullbale();
            $table->date('exam_date')->nullbale();
            $table->string('exam_start_time')->nullbale();
            $table->string('exam_end_time')->nullbale();
            $table->string('exam_venue')->nullbale();
            $table->boolean('exam_taken')->dafault(0);
            $table->integer('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('setting_campus')->onDelete('cascade');
            $table->integer('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onDelete('cascade');
            $table->integer('academic_year')->unsigned()->nullable();
            $table->foreign('academic_year')->references('id')->on('academics_year')->onDelete('cascade');
            $table->integer('academic_level')->unsigned()->nullable();
            $table->foreign('academic_level')->references('id')->on('academics_level')->onDelete('cascade');
            $table->integer('batch')->unsigned()->nullable();
            $table->foreign('batch')->references('id')->on('batch')->onDelete('cascade');
            $table->integer('section')->unsigned()->nullable();
            $table->foreign('section')->references('id')->on('section')->onDelete('cascade');
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
        Schema::dropIfExists('applicant_exam_settings');
    }
}
