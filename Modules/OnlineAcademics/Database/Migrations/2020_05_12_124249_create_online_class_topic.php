<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineClassTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_class_topics', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('institute_id');
			$table->integer('campus_id');
			$table->integer('academic_year_id')->nullable();
			$table->string('academic_year')->nullable();
			$table->integer('academic_level_id')->nullable();
			$table->string('academic_level')->nullable();
			$table->integer('academic_class_id')->nullable();
			$table->string('academic_class')->nullable();
			$table->integer('academic_section_id')->nullable();
			$table->string('academic_section')->nullable();
			$table->integer('class_subject_id')->nullable();
			$table->string('class_subject')->nullable();
			$table->string('class_teacher')->nullable();
			$table->string('class_topic')->nullable();
			$table->string('file_path');
			$table->string('status')->default(0);
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
        Schema::dropIfExists('online_class_topics');
    }
}
