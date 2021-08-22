<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicsClassSubjectTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_subject_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_subject_id')->unsigned()->default(0);
            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
            $table->integer('employee_id')->unsigned()->default(0);
            $table->foreign('employee_id')->references('id')->on('employee_informations')->onDelete('cascade');
            $table->string('status')->default('(not set)');
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('class_subject_teachers');
    }
}
