<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicGradeScaleWeightedAverage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_grade_scale_weighted_average', function (Blueprint $table) {
            $table->increments('id');
                $table->integer('marks')->unsigned()->nullable();

            $table->integer('ass_cat_id')->unsigned()->nullable();
            $table->foreign('ass_cat_id')->references('id')->on('academics_grade_categories')->onDelete('cascade');

            $table->integer('grade_scale_id')->unsigned()->nullable();
            $table->foreign('grade_scale_id')->references('id')->on('academics_grades')->onDelete('cascade');

            $table->integer('level_id')->unsigned()->nullable();
            $table->foreign('level_id')->references('id')->on('academics_level')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('batch_id')->unsigned()->nullable();
            $table->foreign('batch_id')->references('id')->on('batch')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('academic_grade_scale_weighted_average');
    }
}
