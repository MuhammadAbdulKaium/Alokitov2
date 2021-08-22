<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassPeriodCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_period_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default('not set');
            $table->integer('institute')->unsigned()->default(0);
            $table->integer('campus')->unsigned()->default(0);
            $table->integer('academic_year')->unsigned()->default(0);
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
        Schema::dropIfExists('class_period_categories');
    }
}
