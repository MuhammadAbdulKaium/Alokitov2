<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSectionPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_section_period', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('cs_shift')->unsigned()->nullable();
            $table->integer('cs_period_category')->unsigned()->nullable();
            $table->foreign('cs_period_category')->references('id')->on('class_period_categories')->onDelete('cascade');
            $table->integer('academic_year')->unsigned()->nullable();
            $table->foreign('academic_year')->references('id')->on('academics_year')->onDelete('cascade');
            $table->integer('academic_level')->unsigned()->nullable();
            $table->foreign('academic_level')->references('id')->on('academics_level')->onDelete('cascade');
            $table->integer('batch')->unsigned()->nullable();
            $table->foreign('batch')->references('id')->on('batch')->onDelete('cascade');
            $table->integer('section')->unsigned()->nullable();
            $table->foreign('section')->references('id')->on('section')->onDelete('cascade');
            $table->integer('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onDelete('cascade');
            $table->integer('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('setting_campus')->onDelete('cascade');
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
        Schema::dropIfExists('class_section_period');
    }
}
