<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetWeightageConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_weightage_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_year_id');
            $table->integer('semester_id');
            $table->integer('type');
            $table->integer('exam_id')->nullable();
            $table->integer('performance_cat_id')->nullable();
            $table->integer('mark');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadet_weightage_config');
    }
}
