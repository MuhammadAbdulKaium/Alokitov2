<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdCardTemplateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('id_card_template_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('temp_id')->unsigned()->nullable();
            $table->boolean('temp_type')->nullable();
            $table->json('setting')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('campus')->unsigned()->nullable();
            $table->integer('institute')->unsigned()->nullable();
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
        Schema::dropIfExists('id_card_template_settings');
    }
}
