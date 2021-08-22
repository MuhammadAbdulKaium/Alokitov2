<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('batch', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campus')->unsigned()->nullable();
            $table->foreign('campus')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('institute')->unsigned()->nullable();
            $table->foreign('institute')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('academics_year_id')->unsigned()->default(0);
            $table->foreign('academics_year_id')->references('id')->on('academics_year')->onDelete('cascade');
            $table->integer('academics_level_id')->unsigned()->default(0);
            $table->foreign('academics_level_id')->references('id')->on('academics_level')->onDelete('cascade');
            $table->integer('division_id')->unsigned()->nullable();
            $table->foreign('division_id')->references('id')->on('academics_division')->onDelete('cascade');
            $table->string('batch_name');
            $table->string('batch_alias');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('batch');
    }
}
