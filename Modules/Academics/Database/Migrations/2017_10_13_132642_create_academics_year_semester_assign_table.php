<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicsYearSemesterAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics_year_semester_assign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_year')->unsigned()->nullable();
            $table->foreign('academic_year')->references('id')->on('academics_year')->onDelete('cascade');
            $table->integer('academic_level')->unsigned()->nullable();
            $table->foreign('academic_level')->references('id')->on('academics_level')->onDelete('cascade');
            $table->integer('batch')->unsigned()->nullable();
            $table->foreign('batch')->references('id')->on('batch')->onDelete('cascade');
            $table->integer('semester_id')->unsigned()->nullable();
            $table->foreign('semester_id')->references('id')->on('academics_year_semesters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academics_year_semester_assign');
    }
}
