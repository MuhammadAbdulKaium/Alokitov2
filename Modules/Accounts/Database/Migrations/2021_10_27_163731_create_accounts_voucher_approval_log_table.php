<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsVoucherApprovalLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_voucher_approval_log', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable();
            $table->integer('voucher_id')->nullable();
            $table->tinyInteger('voucher_type')->nullable()->comment('1=Payment voucher, 2=Receive voucher, 3=Journal voucher, 4=Contra voucher');
            $table->integer('approval_id')->nullable();
            $table->tinyInteger('is_role')->default(0)->nullable()->comment('1=yes,0=no');
            $table->integer('approval_layer')->nullable();
            $table->tinyInteger('action_status')->nullable()->comment('0=pending,1=apprroved,2=reject');
            $table->string('comments')->nullable();
            $table->integer('institute_id');
            $table->integer('campus_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts_voucher_approval_log');
    }
}
