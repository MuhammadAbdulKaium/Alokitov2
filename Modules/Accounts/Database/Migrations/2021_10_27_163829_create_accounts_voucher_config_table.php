<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsVoucherConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_voucher_config', function (Blueprint $table) {
            $table->id();
            $table->integer('type_of_voucher')->comment('1=Payment voucher Bank,2=Payment voucher Cash,3=Receive voucher Bank,4=Receive voucher Cash,5=Journal voucher,6=Contra voucher');
            $table->string('numbering', 100);
            $table->integer('numeric_part')->nullable();
            $table->string('suffix', 100)->nullable();
            $table->string('voucher_name', 100);
            $table->integer('starting_number')->nullable();
            $table->string('prefix', 100)->nullable();
            $table->enum('status', ['1', '0'])->nullable()->comment('1=Active, 0=Inactive');
            $table->integer('campus_id');
            $table->integer('institute_id');
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts_voucher_config');
    }
}
