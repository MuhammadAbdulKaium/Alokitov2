<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_address', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicant_id')->unsigned()->nullable();
            $table->foreign('applicant_id')->references('id')->on('applicant_user')->onDelete('cascade');
            $table->enum('type', array('PRESENT','PERMANENT'))->nullable();
            $table->string('address')->default('Not set');
            $table->string('house')->default('Not set');
            $table->string('street')->default('Not set');
            $table->integer('city_id')->unsigned()->default(0);
            $table->foreign('city_id')->references('id')->on('setting_city')->onDelete('cascade');
            $table->integer('state_id')->unsigned()->default(0);
            $table->foreign('state_id')->references('id')->on('setting_state')->onDelete('cascade');
            $table->integer('country_id')->unsigned()->default(0);
            $table->foreign('country_id')->references('id')->on('setting_country')->onDelete('cascade');
            $table->integer('zip')->unsigned()->default(0);
            $table->string('phone', 20)->nullable();
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
        Schema::dropIfExists('applicant_address');
    }
}
