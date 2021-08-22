<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectAssessmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_assessment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sub_ass_id')->unsigned()->nullable();
            $table->foreign('sub_ass_id')->references('id')->on('subject_assessments')->onDelete('cascade');
            $table->integer('sub_id')->unsigned()->nullable();
            $table->foreign('sub_id')->references('id')->on('subject')->onUpdate('cascade')->onDelete('cascade');
            $table->json('assessment_marks')->nullable();
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
        Schema::dropIfExists('subject_assessment_details');
    }
}
