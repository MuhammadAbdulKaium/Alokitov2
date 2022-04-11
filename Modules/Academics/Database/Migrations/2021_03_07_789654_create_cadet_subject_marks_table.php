<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetSubjectMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_subject_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id');
            $table->integer('exam_id');
            $table->integer('batch_id');
            $table->integer('full_marks');
            $table->integer('pass_marks');
            $table->integer('full_mark_conversion');
            $table->integer('pass_mark_conversion');
            $table->longText('marks');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('campus_id');
            $table->integer('institute_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadet_subject_marks');
    }
}
