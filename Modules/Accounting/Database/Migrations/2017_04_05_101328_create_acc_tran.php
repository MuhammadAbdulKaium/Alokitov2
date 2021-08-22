<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccTran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_tran', function (Blueprint $table){
            $table->increments('id');
            $table->integer('tran_seq');
            $table->integer('tran_serial');
            $table->integer('acc_voucher_type_id');
            $table->date('tran_date');
            $table->integer('acc_charts_id');
            $table->double('tran_amt_dr')->default(0);
            $table->double('tran_amt_cr')->default(0);
            $table->string('tran_details',1000)->nullable();

                //0- deleted
                //1- final_approve
                //2- waiting for first_approve
                //3- waiting for second_approve
                //4- waiting for third_approve
            $table->tinyInteger('status')->default(2);
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
        Schema::dropIfExists('acc_tran');
    }
}
