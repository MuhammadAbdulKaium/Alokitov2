<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentGradeExtraBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_grade_extra_books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_id')->unsigned()->nullable();
            $table->json('extra_marks')->nullable();
            $table->integer('semester')->unsigned()->nullable();
            $table->integer('section')->unsigned()->nullable();
            $table->foreign('section')->references('id')->on('section')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('batch')->unsigned()->nullable();
            $table->foreign('batch')->references('id')->on('batch')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('a_level')->unsigned()->nullable();
            $table->integer('a_year')->unsigned()->nullable();
            $table->integer('campus')->unsigned()->nullable();
            $table->foreign('campus')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('institute')->unsigned()->nullable();
            $table->foreign('institute')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('student_grade_extra_books');
    }
}
