<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AcademicsGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('not set');
            $table->integer('grade_scale_id')->unsigned()->nullable();
            $table->foreign('grade_scale_id')->references('id')->on('academics_grade_scales')->onDelete('cascade');
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
        Schema::dropIfExists('academics_grades');
    }
}
