<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryItemSerialDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_item_serial_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->integer('serial_id');
            $table->string('serial_code', 100);
            $table->integer('serial_int_no')->nullable();
            $table->string('barcode',255)->nullable();
            $table->string('qrcode',255)->nullable();
            $table->integer('stock_in_store_id')->nullable();
            $table->integer('stock_in_qty')->nullable();
            $table->integer('current_stock')->nullable();
            $table->decimal('price', 18, 6)->nullable();
            $table->string('stock_in_from')->nullable();
            $table->string('stock_out_from')->nullable();
            $table->date('stock_in_date')->nullable();
            $table->date('stock_out_date')->nullable();
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
        Schema::dropIfExists('inventory_item_serial_details');
    }
}
