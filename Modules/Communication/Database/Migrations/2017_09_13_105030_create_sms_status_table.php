<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id')->nullable();
            $table->string('status')->nullable();
            $table->string('status_text')->nullable();
            $table->string('error_code')->nullable();
            $table->string('error_text')->nullable();
            $table->string('sms_count')->nullable();
            $table->double('current_credit')->nullable();
            $table->integer('sms_logid')->nullable();
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
        Schema::dropIfExists('sms_status');
    }
}
