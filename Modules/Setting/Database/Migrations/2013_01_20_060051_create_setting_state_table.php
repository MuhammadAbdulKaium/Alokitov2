<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('setting_state', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('country_id')->unsigned()->default(0);
            $table->foreign('country_id')->references('id')->on('setting_country')->onDelete('cascade');

            $table->string('name');
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
        Schema::dropIfExists('setting_state');
    }
}
