<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoSmsModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_sms_module', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status_code');
            $table->integer('status');
            $table->integer('sms_temp_id');
            $table->integer('ins_id');
            $table->integer('campus_id');
            $table->mediumText('description');
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('auto_sms_module');
    }
}
