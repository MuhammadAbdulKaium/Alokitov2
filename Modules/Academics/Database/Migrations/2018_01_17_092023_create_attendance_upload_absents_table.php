<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceUploadAbsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_upload_absents', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('h_id')->unsigned()->nullable();
            //$table->foreign('h_id')->references('id')->on('attendance_upload_history')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('std_id')->unsigned()->nullable();
            $table->foreign('std_id')->references('id')->on('student_informations')->onUpdate('cascade')->onDelete('cascade');

            $table->string('std_gr_no')->nullable();
            $table->string('card_no')->nullable();
            $table->date('date')->nullable();

            $table->integer('academic_year')->unsigned()->nullable();
            $table->foreign('academic_year')->references('id')->on('academics_year')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('level')->unsigned()->nullable();
            $table->foreign('level')->references('id')->on('academics_level')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('batch')->unsigned()->nullable();
            $table->foreign('batch')->references('id')->on('batch')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('section')->unsigned()->nullable();
            $table->foreign('section')->references('id')->on('section')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('attendance_upload_absents');
    }
}
