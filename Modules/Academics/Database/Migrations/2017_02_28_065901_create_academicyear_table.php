<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicyearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academicyear', function (Blueprint $table) {
            $table->increments('id');
            $table->string('academic_name');
            $table->date('start_date');
            $table->date('end_date');
            // $table->integer('created_by');
            // $table->integer('deleted_by');
            // $table->integer('updated_by');
            $table->integer('is_deleted')->default(0);
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
        Schema::dropIfExists('academicyear');
    }
}