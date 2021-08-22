<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AcademicsGradesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics_grade_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('grade_id')->unsigned()->nullable();
            $table->foreign('grade_id')->references('id')->on('academics_grades')->onDelete('cascade');
            $table->string('name')->default('not set');
            $table->integer('min_per')->unsigned()->default(0);
            $table->integer('max_per')->unsigned()->default(0);
            $table->float('points')->unsigned()->default(0);
            $table->integer('sorting_order')->unsigned()->default(0);
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
        Schema::dropIfExists('academics_grade_details');
    }
}
