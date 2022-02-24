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
            //$table->foreign('applicant_id')->references('id')->on('applicant_user')->onDelete('cascade');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('std_name_bn')->nullable();
            $table->boolean('gender')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('std_photo')->nullable();
            $table->string('invoice')->nullable();
            $table->string('nationality')->nullable();
            $table->float('amount',10,3)->nullable();



            $table->string('last_certificate_name')->nullable();
            $table->string('group_id')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('passing_year')->nullable();
            $table->string('board_name')->nullable();
            $table->string('institute_full_name')->nullable();
            $table->string('gpa')->nullable();
            $table->text('subject_wise_mark')->nullable();
            $table->timestamps();
            $table->softDeletes();


          /*  lastCertification: null,
            group_id: null,
            registrationNumber: null,
            rollNumber: null,
            passingYear: null,
            boardName: null,
            instituteFullName: null,
            gpa: null,*/

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
