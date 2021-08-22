<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetMessTableHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_mess_table_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('mess_table_id');
            $table->integer('seat_no');
            $table->integer('person_type');
            $table->integer('person_id');
            $table->integer('activity');
            $table->timestamps();
            $table->integer('created_by');
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
        Schema::dropIfExists('cadet_mess_table_histories');
    }
}
