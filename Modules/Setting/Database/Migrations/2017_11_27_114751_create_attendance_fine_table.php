<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceFineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_fine_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ins_id');
            $table->integer('campus_id');
            $table->string('setting_type')->nullable();
            $table->double('amount');
            $table->time('form_entry_time');
            $table->time('to_entry_time');
            $table->integer('sorting_order');
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('attendance_fine');
    }
}
