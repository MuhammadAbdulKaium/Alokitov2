<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicFourthSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_additional_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_id')->unsigned()->nullable();
            $table->integer('main_class_sub_id')->unsigned()->nullable();
            $table->integer('fourth_class_sub_id')->unsigned()->nullable();
            $table->integer('batch')->unsigned()->nullable();
            $table->integer('section')->unsigned()->nullable();
            $table->integer('a_year')->unsigned()->nullable();
            $table->integer('campus')->unsigned()->nullable();
            $table->integer('institute')->unsigned()->nullable();
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
        Schema::dropIfExists('academic_fourth_subjects');
    }
}
