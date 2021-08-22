<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('employee_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('title', array('Mr.', 'Mrs.', 'Ms.', 'Prof.', 'Dr.'))->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('alias')->nullable();
            $table->enum('gender', array('Male', 'Female'))->nullable();
            $table->date('dob')->nullable();
            $table->date('doj')->nullable();
            $table->integer('department')->unsigned()->nullable();
            $table->foreign('department')->references('id')->on('employee_departments')->onDelete('cascade');
            $table->integer('designation')->unsigned()->nullable();
            $table->foreign('designation')->references('id')->on('employee_designations')->onDelete('cascade');
            $table->integer('category')->unsigned()->nullable();
            $table->string('email')->default('Not avaliable');
            $table->string('phone')->nullable();
            $table->enum('blood_group', array('Unknown', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'))->nullable();
            $table->string('birth_place')->nullable();
            $table->integer('religion')->unsigned()->nullable();
            $table->enum('marital_status', array('MARRIED', 'UNMARRIED', 'DIVORCED'))->nullable();
            $table->integer('nationality')->unsigned()->nullable();
            $table->integer('experience_year')->unsigned()->nullable();
            $table->integer('experience_month')->unsigned()->nullable();
            $table->integer('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sort_order')->unsigned()->nullable();
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
        Schema::dropIfExists('employee_informations');
    }
}
