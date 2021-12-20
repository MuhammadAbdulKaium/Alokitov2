<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('successes', function (Blueprint $table) {
            $table->id();
            $table->integer('campus_id');
            $table->integer('institute_id');
            $table->integer('passing_year');
            $table->integer('total_examine');
            $table->integer('psc_passing_rate')->nullable();
            $table->integer('jsc_passing_rate')->nullable();
            $table->integer('ssc_passing_rate')->nullable();
            $table->integer('hsc_passing_rate')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('successes');
    }
}
