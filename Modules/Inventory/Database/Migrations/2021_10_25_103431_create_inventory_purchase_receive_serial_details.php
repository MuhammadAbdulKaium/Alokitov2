<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPurchaseReceiveSerialDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_receive_serial_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pur_receive_id')->nullable();
            $table->integer('pur_receive_details_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->integer('serial_details_id')->nullable();
            $table->string('serial_code',255)->nullable();
            $table->integer('institute_id');
            $table->integer('campus_id');
            $table->softDeletes();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('inventory_purchase_receive_serial_details');
    }
}
