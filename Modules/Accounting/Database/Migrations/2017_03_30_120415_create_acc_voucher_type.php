<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccVoucherType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_voucher_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_code');
            $table->string('voucher_name');
            $table->integer('voucher_type_id');
            $table->integer('acc_charts_id')->nullable();
            $table->string('notes',1000)->nullable();
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
        Schema::dropIfExists('acc_voucher_type');
    }
}
