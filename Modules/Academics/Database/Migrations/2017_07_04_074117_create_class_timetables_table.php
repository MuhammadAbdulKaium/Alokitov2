<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_timetables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('day')->unsigned()->default(0);
            $table->integer('period')->unsigned()->default(0);
            $table->boolean('shift')->unsigned()->default(0);
            $table->integer('room')->unsigned()->default(0);
            $table->integer('batch')->unsigned()->default(0);
            $table->integer('section')->unsigned()->default(0);
            $table->integer('subject')->unsigned()->default(0);
            $table->integer('teacher')->unsigned()->default(0);
            $table->integer('campus')->unsigned()->default(0);
            $table->integer('institute')->unsigned()->default(0);
            $table->integer('academic_year')->unsigned()->nullable();
            $table->foreign('academic_year')->references('id')->on('academics_year')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('class_timetables');
    }
}
