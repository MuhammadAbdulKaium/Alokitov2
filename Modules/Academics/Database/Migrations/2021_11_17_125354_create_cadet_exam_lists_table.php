<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetExamListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_exam_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_year_id');
            $table->integer('term_id');
            $table->integer('exam_id');
            $table->integer('batch_id');
            $table->integer('section_id');
            $table->integer('publish_status')->comment("0=none, 1=pending, 2=published, 3=rejected");
            $table->integer('step');
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('cadet_exam_lists');
    }
}
