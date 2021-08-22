<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('period_name')->default('not set');
            $table->integer('campus')->unsigned()->default(0);
            $table->integer('institute')->unsigned()->default(0);
            $table->integer('academic_year')->unsigned()->default(0);
            $table->integer('period_shift')->unsigned()->default(0);
            $table->integer('period_category')->unsigned()->default(0);
            $table->integer('period_start_hour')->unsigned()->default(0);
            $table->integer('period_start_min')->unsigned()->default(0);
            $table->boolean('period_start_meridiem')->unsigned()->default(0);
            $table->integer('period_end_hour')->unsigned()->default(0);
            $table->integer('period_end_min')->unsigned()->default(0);
            $table->boolean('period_end_meridiem')->unsigned()->default(0);
            $table->boolean('is_break')->unsigned()->default(0);
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
        Schema::dropIfExists('class_periods');
    }
}
