<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCadetStockProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_stock_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_description',255)->nullable();
            $table->string('product_name', 255);
            $table->integer('stock_group');
            $table->integer('unit');
            $table->tinyInteger('has_fraction');
            $table->integer('round_of')->nullable();
            $table->integer('numeric_part')->nullable();
            $table->integer('use_serial');
            $table->string('prefix',100)->nullable();
            $table->string('suffix',100)->nullable();
            $table->string('separator_symbol',100)->nullable();
            $table->integer('min_stock');
            $table->integer('item_type');
            $table->string('alias');
            $table->string('sku');
            $table->string('barcode')->nullable();
            $table->string('qrcode')->nullable();
            $table->integer('code_type_id');
            $table->integer('category_id');
            $table->integer('warrenty_month')->nullable();
            $table->integer('reorder_qty')->nullable();
            $table->string('additional_remarks')->nullable();
            $table->longText('store_tagging')->nullable();
            $table->string('image')->nullable();
            $table->integer('decimal_point_place')->nullable();
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
        Schema::dropIfExists('cadet_stock_products');
    }
}
