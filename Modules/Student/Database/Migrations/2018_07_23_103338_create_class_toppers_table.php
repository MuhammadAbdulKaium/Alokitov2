<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassToppersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_toppers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('gr_no')->nullable();
            $table->boolean('status')->default(1);

            $table->integer('std_id')->unsigned()->nullable();
            $table->foreign('std_id')->references('id')->on('student_informations')->onDelete('restrict');

            $table->integer('section')->unsigned();
            $table->foreign('section')->references('id')->on('section')->onDelete('cascade');

            $table->integer('batch')->unsigned();
            $table->foreign('batch')->references('id')->on('batch')->onDelete('cascade');

            $table->integer('academic_level')->unsigned();
            $table->foreign('academic_level')->references('id')->on('academics_level')->onDelete('cascade');

            $table->integer('academic_year')->unsigned()->default(0);
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
        Schema::dropIfExists('class_toppers');
    }
}
