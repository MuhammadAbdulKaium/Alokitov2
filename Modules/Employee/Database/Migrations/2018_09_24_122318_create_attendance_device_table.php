<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeAttendanceDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_device', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card');
            $table->date('access_date');
            $table->time('access_time');
            $table->string('registration_id');
            $table->string('person_type');
            $table->integer('institute_id');
            $table->integer('campus_id')->nullable();
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
        Schema::dropIfExists('attendance_device');
    }
}
