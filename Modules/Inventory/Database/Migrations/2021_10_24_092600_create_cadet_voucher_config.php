<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCadetVoucherConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_voucher_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_of_voucher')->comment('1=New Requisition,2=Issue From Inventory,3=Store Transfer Requisition,4=Store Transfer,5=Purchase Requisition,6=Purchase Order,7=Purchase Receive,8=Purchase Return,9=Sales Order,10=Sales/Delivery Challan,11=Sales Return,12=Stock In,13=Stock Out,14=Comparative Statement,15=General Purchase Order,16=LC Purchase Order,
17=Purchase Invoice');
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
        Schema::dropIfExists('cadet_voucher_config');
    }
}
