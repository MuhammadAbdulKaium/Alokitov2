<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_information', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campus_id')->unsigned();
            $table->foreign('campus_id')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('institute_id')->unsigned();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');
            $table->string('school_name')->nullable();
            $table->string('school_address')->nullable();
            $table->string('school_phone', 15)->nullable();
            $table->string('school_email')->nullable()->unique();
            $table->string('school_fb')->nullable();
            $table->string('school_logo')->nullable();
            $table->text('school_contact')->nullable();
            $table->text('school_history')->nullable();
            $table->text('school_mission')->nullable();
            $table->text('school_structure')->nullable();
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
        Schema::dropIfExists('website_information');
    }
}
