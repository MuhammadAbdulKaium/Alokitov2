<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_bank', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_code');
            $table->string('bank_name');
            $table->string('bank_acc_no');
            $table->string('bank_acc_name');
            $table->integer('chart_parent');
            $table->integer('company_id')->nullable();
            $table->integer('brunch_id')->nullable();
            $table->string('notes',1000)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('created_by')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('updated_date')->nullable();
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
        Schema::dropIfExists('acc_bank');
    }
}
