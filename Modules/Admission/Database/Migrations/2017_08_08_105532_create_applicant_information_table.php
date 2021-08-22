<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_information', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicant_id')->unsigned()->nullable();
            $table->foreign('applicant_id')->references('id')->on('applicant_user')->onDelete('cascade');

            $table->string('std_name')->nullable();
            $table->string('std_name_bn')->nullable();

            $table->string('father_name')->nullable();
            $table->string('father_name_bn')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_education')->nullable();

            $table->string('mother_name')->nullable();
            $table->string('mother_name_bn')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_education')->nullable();

            $table->boolean('gender')->nullable();
            $table->date('birth_date')->nullable();

            $table->string('add_per_address')->nullable();
            $table->string('add_per_post')->nullable();
            $table->integer('add_per_city')->unsigned()->nullable();
            $table->integer('add_per_state')->unsigned()->nullable();
            $table->string('add_per_phone')->nullable();

            $table->string('add_pre_address')->nullable();
            $table->string('add_pre_post')->nullable();
            $table->integer('add_pre_city')->unsigned()->nullable();
            $table->integer('add_pre_state')->unsigned()->nullable();
            $table->string('add_pre_phone')->nullable();

            $table->string('gud_name')->nullable();
            $table->string('gud_phone')->nullable();
            $table->string('gud_income')->nullable();
            $table->string('gud_income_bn')->nullable();

            $table->string('psc_gpa')->nullable();
            $table->string('psc_roll')->nullable();
            $table->string('psc_year')->nullable();
            $table->string('psc_school')->nullable();
            $table->string('psc_tes_no')->nullable();
            $table->string('psc_tes_date')->nullable();
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
        Schema::dropIfExists('applicant_information');
    }
}
