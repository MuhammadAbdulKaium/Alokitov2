<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccClosingBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return voidacc_f_year
     */
    public function up()
    {
        Schema::create('acc_closing_balance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('acc_f_year_id')->references('id')->on('acc_f_year');
            $table->integer('acc_charts_id')->references('id')->on('acc_charts');
            $table->float('balance');
            $table->tinyInteger('status')->default(0);
            $table->integer('company_id')->nullable();
            $table->integer('brunch_id')->nullable();
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
        Schema::dropIfExists('acc_closing_balance');
    }
}
