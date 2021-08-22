<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetMessFoodMenuSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_mess_food_menu_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('slot');
            $table->time('time');
            $table->integer('menu_category_id');
            $table->integer('menu_id');
            $table->integer('persons');
            $table->longText('person_details')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('cadet_mess_food_menu_schedules');
    }
}
