<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_parents', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('gud_id')->unsigned()->nullable();
            $table->foreign('gud_id')->references('id')->on('student_guardians')->onDelete('cascade');

            $table->integer('std_id')->unsigned()->nullable();
            $table->foreign('std_id')->references('id')->on('student_informations')->onDelete('cascade');

            $table->boolean('is_emergency')->default(0);
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
        Schema::dropIfExists('student_parents');
    }
}
