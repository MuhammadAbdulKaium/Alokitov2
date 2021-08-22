<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_status', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('status')->default(0);

            $table->integer('semester')->unsigned()->nullable();
            $table->foreign('semester')->references('id')->on('academics_year_semesters')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('section')->unsigned()->nullable();
            $table->foreign('section')->references('id')->on('section')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('batch')->unsigned()->nullable();
            $table->foreign('batch')->references('id')->on('batch')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('level')->unsigned()->nullable();
            $table->foreign('level')->references('id')->on('academics_level')->onUpdate('cascade')->onDelete('cascade');

            $table->json('assessments')->nullable();

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
        Schema::dropIfExists('exam_status');
    }
}
