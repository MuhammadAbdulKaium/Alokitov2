<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicsLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics_level', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level_name')->nullable();
            $table->string('level_code')->nullable();
            $table->boolean('level_type')->default(0); // ex- Zero (0) for School and One (1) for College
            $table->integer('is_active')->default(1);
            $table->integer('academics_year_id')->unsigned()->nullable();
            $table->foreign('academics_year_id')->references('id')->on('academics_year')->onDelete('cascade');
            $table->integer('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('academics_level');
    }
}
