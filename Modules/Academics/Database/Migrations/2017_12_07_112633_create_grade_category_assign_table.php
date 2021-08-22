<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeCategoryAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics_grade_category_assign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('result_count')->unsigned()->nullable();
            $table->integer('grade_cat_id')->unsigned()->nullable();
            $table->foreign('grade_cat_id')->references('id')->on('academics_grade_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('level')->unsigned()->nullable();
            $table->foreign('level')->references('id')->on('academics_level')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('batch')->unsigned()->nullable();
            $table->foreign('batch')->references('id')->on('batch')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('section')->unsigned()->nullable();
            $table->foreign('section')->references('id')->on('section')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('semester')->unsigned()->nullable();
            $table->foreign('semester')->references('id')->on('academics_year_semesters')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('grade_category_assign');
    }
}
