<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryStoreWiseItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_store_wise_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->integer('store_id');
            $table->decimal('avg_cost_price',18,6)->nullable()->comment('weight avg method calculate');
            $table->decimal('current_stock',18,6)->nullable()->comment('remaining stock');
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
        Schema::dropIfExists('inventory_store_wise_item');
    }
}
