<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicsClassSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_id')->unsigned()->default(0);
            $table->foreign('class_id')->references('id')->on('batch')->onDelete('cascade');
            $table->integer('section_id')->unsigned()->default(0);
            $table->foreign('section_id')->references('id')->on('section')->onDelete('cascade');
            $table->integer('subject_id')->unsigned()->default(0);
            $table->foreign('subject_id')->references('id')->on('subject')->onDelete('cascade');
            $table->string('subject_code')->default('(not set)');
            $table->string('subject_type')->default('(not set)');
            $table->string('subject_credit')->default('(not set)');
            $table->integer('pass_mark')->unsigned()->default(0);
            $table->integer('exam_mark')->unsigned()->default(0);
            $table->integer('sorting_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(0);
            $table->boolean('is_countable')->default(1);
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
        Schema::dropIfExists('class_subjects');
    }
}
