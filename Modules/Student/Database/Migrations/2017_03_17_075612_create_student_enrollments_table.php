<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_id')->unsigned()->default(0);
            $table->foreign('std_id')->references('id')->on('student_informations')->onDelete('restrict');
            $table->string('gr_no')->nullable();
            $table->integer('academic_level')->unsigned()->default(0);
            $table->foreign('academic_level')->references('id')->on('academics_level')->onDelete('restrict');
            $table->integer('batch')->unsigned()->default(0);
            $table->foreign('batch')->references('id')->on('batch')->onDelete('restrict');
            $table->integer('section')->unsigned()->default(0);
            $table->foreign('section')->references('id')->on('section')->onDelete('restrict');
            $table->integer('academic_year')->unsigned()->default(0);
            $table->foreign('academic_year')->references('id')->on('academics_year')->onDelete('restrict');
            $table->integer('admission_year')->unsigned()->default(0);
            $table->foreign('admission_year')->references('id')->on('academics_admissionyear')->onDelete('restrict');
            $table->date('enrolled_at');
            $table->string('enroll_status')->default("Not available");
            $table->string('batch_status')->default("Not available");
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
        Schema::dropIfExists('student_enrollments');
    }
}
