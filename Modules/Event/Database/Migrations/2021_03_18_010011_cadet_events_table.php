<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CadetEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_events', function (Blueprint $table) {
                $table->increments('id');
                $table->string('event_name');
                $table->integer('category_id');
                $table->integer('sub_category_id');
                $table->integer('activity_id');
                $table->integer('status');
                $table->text('remarks')->nullable();
                $table->json('employee_id');
                $table->timestamps();
                $table->softDeletes();
                $table->integer('created_by');
                $table->integer('updated_by')->nullable();
                $table->integer('deleted_by')->nullable();
                $table->integer('campus');
                $table->integer('institute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
