<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryVoucherApprovalLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_voucher_approval_log', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('date')->nullable();
            $table->integer('voucher_id')->nullable();
            $table->integer('voucher_details_id')->nullable();
            $table->string('voucher_type', 20)->nullable();
            $table->integer('approval_id')->nullable();
            $table->tinyInteger('is_role')->default(0)->nullable()->comment('1=yes,0=no');
            $table->integer('approval_layer')->nullable();
            $table->tinyInteger('action_status')->nullable()->comment('0=pending,1=apprroved,2=partial approve,3=reject');
            $table->string('comments')->nullable();
            $table->integer('institute_id');
            $table->integer('campus_id');
            $table->tinyInteger('valid')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_voucher_approval_log');
    }
}
