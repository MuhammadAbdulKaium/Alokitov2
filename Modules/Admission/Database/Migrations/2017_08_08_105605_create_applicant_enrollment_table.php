<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantEnrollmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_enrollment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicant_id')->unsigned()->nullable();
            $table->foreign('applicant_id')->references('id')->on('applicant_user')->onDelete('cascade');
            $table->integer('academic_year')->unsigned()->nullable();
            $table->foreign('academic_year')->references('id')->on('academics_year')->onDelete('cascade');
            $table->integer('academic_level')->unsigned()->nullable();
            $table->foreign('academic_level')->references('id')->on('academics_level')->onDelete('cascade');
            $table->integer('batch')->unsigned()->nullable();
            $table->foreign('batch')->references('id')->on('batch')->onDelete('cascade');
            $table->integer('section')->unsigned()->nullable();
            $table->foreign('section')->references('id')->on('section')->onDelete('cascade');
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
        Schema::dropIfExists('applicant_enrollment');
    }
}
