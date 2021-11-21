<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryAllStockItemIn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_all_stock_item_in', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('item_id');
            $table->integer('stock_in_id')->nullable();
            $table->integer('stock_in_details_id')->nullable();
            $table->integer('store_id')->nullable();
            $table->decimal('qty',18,6)->nullable();
            $table->decimal('rate',18,6)->nullable();
            $table->decimal('hand_qty',18,6)->nullable();
            $table->string('stock_in_from',20)->nullable();
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('inventory_all_stock_item_in');
    }
}
