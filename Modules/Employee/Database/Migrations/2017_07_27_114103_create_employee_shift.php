<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeShift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shiftName');
            $table->integer('firstHoliday')->nullable();
            $table->integer('secondHoliday')->nullable();
            $table->time('shiftStartTime');
            $table->time('shiftEndTime');
            $table->time('lateInTime')->nullable();
            $table->time('absentInTime')->nullable();
            $table->time('lunchStartTime')->nullable();
            $table->time('lunchEndTime')->nullable();
            $table->time('overTimeStart')->nullable();
            $table->time('overTimeEnd')->nullable();
            $table->time('extraOverTimeStart')->nullable();
            $table->time('extraOverTimeEnd')->nullable();
            $table->time('earlyOutTime')->nullable();
            $table->time('lastOutTime');
            $table->integer('lateDayAllow')->nullable();
            $table->integer('outTimeGrace')->nullable();
            $table->integer('company_id');
            $table->integer('brunch_id')->nullable();
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
        Schema::dropIfExists('shift');
    }
}
