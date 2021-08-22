<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectGroupAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_group_assign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sub_id')->unsigned()->nullable();
            $table->foreign('sub_id')->references('id')->on('subject')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sub_group_id')->unsigned()->nullable();
            $table->foreign('sub_group_id')->references('id')->on('subject_group')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('subject_group_assign');
    }
}
