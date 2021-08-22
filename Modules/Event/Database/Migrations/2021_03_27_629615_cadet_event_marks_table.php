<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CadetEventMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_event_marks', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('event_id');
                $table->integer('performance_type_id');
                $table->integer('performance_category_id');
                $table->timestamp('date_time');
                $table->string('venue')->nullable();
                $table->integer('team_id');
                $table->integer('house_id')->nullable();
                $table->integer('batch_id')->nullable();
                $table->integer('section_id')->nullable();
                $table->integer('student_id')->nullable();
                $table->integer('mark')->nullable();
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
        Schema::dropIfExists('cadet_event_marks');
    }
}
