<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentEnrollmentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_enrollment_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enroll_id')->unsigned();
            $table->foreign('enroll_id')->references('id')->on('student_enrollments')->onDelete('cascade');

            $table->string('gr_no')->nullable();

            $table->integer('section')->unsigned();
            $table->foreign('section')->references('id')->on('section')->onDelete('cascade');

            $table->integer('batch')->unsigned();
            $table->foreign('batch')->references('id')->on('batch')->onDelete('cascade');

            $table->integer('academic_level')->unsigned();
            $table->foreign('academic_level')->references('id')->on('academics_level')->onDelete('cascade');

            $table->integer('academic_year')->unsigned()->default(0);
            $table->foreign('academic_year')->references('id')->on('academics_year')->onDelete('cascade');

            $table->date('enrolled_at');
            $table->string('enroll_status')->default("Not available");
            $table->string('remark', 1000)->default("Not available");

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
        Schema::dropIfExists('student_enrollment_history');
    }
}
