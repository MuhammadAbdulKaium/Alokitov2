<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_summary', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('es_id')->unsigned()->nullable();
            $table->foreign('es_id')->references('id')->on('exam_status')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('std_id')->unsigned()->nullable();
            $table->foreign('std_id')->references('id')->on('student_informations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('merit')->unsigned()->nullable();
            $table->integer('merit_wa')->unsigned()->nullable();
            $table->integer('merit_extra')->unsigned()->nullable();
            $table->integer('marks')->unsigned()->nullable();
            $table->integer('marks_wa')->unsigned()->nullable();
            $table->integer('marks_extra')->unsigned()->nullable();
            $table->json('result')->nullable();
            $table->json('result_wa')->nullable();
            $table->json('result_extra')->nullable();
            $table->json('attendance')->nullable();
            $table->string('comments', 255)->nullable();
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
        Schema::dropIfExists('exam_summary');
    }
}
