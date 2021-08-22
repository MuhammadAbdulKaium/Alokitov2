<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineclasshistorys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_historys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('topic_date')->nullable();
            $table->string('routine_time')->nullable();
            $table->string('conduct_time')->nullable();
            $table->string('topic')->nullable();
            $table->string('subject_id')->nullable();
            $table->string('subject_name')->nullable();
            $table->string('class_id')->nullable();
            $table->string('class_name')->nullable();
            $table->string('section_id')->nullable();
            $table->string('section_name')->nullable();
            $table->string('teacher_id')->nullable();
            $table->string('teacher_name')->nullable();
            $table->string('duraction')->nullable();
            $table->string('total')->nullable();
            $table->string('p')->nullable();
            $table->string('a')->nullable();
            $table->string('l')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('');
    }
}
