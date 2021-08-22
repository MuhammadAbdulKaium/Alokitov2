<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentSubject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('division_id');
            $table->string('subject_name');
            $table->string('subject_code');
            $table->string('subject_alias');
            $table->integer('is_deleted')->default(0);
//            $table->integer('is_active',1)->default(1);
            $table->integer('academic_year')->unsigned()->nullable();
            $table->foreign('academic_year')->references('id')->on('academics_year')->onDelete('cascade');
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
        Schema::dropIfExists('subject');
    }
}
