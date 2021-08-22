<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicsAttendanceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics_attendance_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institution_id')->unsigned()->default(0);
            $table->integer('campus_id')->unsigned()->default(0);
            $table->boolean('multiple_sessions')->default(0);
            $table->boolean('subject_wise')->default(0);
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
        Schema::dropIfExists('academics_attendance_settings');
    }
}
