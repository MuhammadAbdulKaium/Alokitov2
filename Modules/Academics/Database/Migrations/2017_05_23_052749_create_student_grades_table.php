<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_id')->unsigned()->default(0);
            $table->integer('mark_id')->unsigned()->default(0);
            $table->integer('scale_id')->unsigned()->default(0);
            $table->integer('class_sub_id')->unsigned()->default(0);
            $table->integer('semester')->unsigned()->default(0);
            $table->integer('academic_year')->unsigned()->default(0);
            $table->integer('section')->unsigned()->nullable();
            $table->foreign('section')->references('id')->on('section')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('batch')->unsigned()->nullable();
            $table->foreign('batch')->references('id')->on('batch')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('student_grades');
    }
}
