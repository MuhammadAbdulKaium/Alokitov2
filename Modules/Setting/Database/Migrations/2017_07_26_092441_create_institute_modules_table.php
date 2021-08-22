<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstituteModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_institute_modules', function (Blueprint $table) {
            $table->integer('institute_id')->unsigned()->default(0);
            $table->integer('module_id')->unsigned()->default(0);


            $table->foreign('institute_id')->references('id')->on('setting_institute')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('module_id')->references('id')->on('setting_modules')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['institute_id', 'module_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_institute_modules');
    }
}
