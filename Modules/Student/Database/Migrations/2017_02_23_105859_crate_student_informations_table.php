<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateStudentInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('institute')->unsigned()->nullable();
            $table->foreign('institute')->references('id')->on('setting_institute')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('campus')->unsigned()->nullable();
            $table->foreign('campus')->references('id')->on('setting_campus')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('type')->unsigned()->default(0)->nullable();
            $table->enum('title', array('Mr.','Mrs.', 'Ms.'))->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('gender', array('Male', 'Female'))->nullable();
            $table->date('dob')->nullable();
            $table->enum('blood_group', array('Unknown','A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'))->nullable();
            $table->string('religion')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('residency')->nullable();
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
        Schema::dropIfExists('student_informations');
    }
}
