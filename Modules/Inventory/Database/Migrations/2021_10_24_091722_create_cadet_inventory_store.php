<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCadetInventoryStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_inventory_store', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_name', 100);
            $table->string('store_address_1', 100);
            $table->string('store_address_2', 100);
            $table->string('store_phone', 100);
            $table->string('store_fax', 100);
            $table->string('store_city', 100);
            $table->integer('category_id');
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
        Schema::dropIfExists('cadet_inventory_store');
    }
}
