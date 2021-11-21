<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPriceCatalogueDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_price_catalogue_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalogue_uniq_id')->nullable()->comment('use it for all operation label');
            $table->integer('catelogue_id')->nullable();
            $table->integer('item_id');
            $table->decimal('from_qty',18,6)->nullable();
            $table->decimal('to_qty',18,6)->nullable();
            $table->decimal('rate',18,6)->nullable();
            $table->date('applicable_from');
            $table->decimal('discount',18,6)->nullable();
            $table->decimal('vat_per',18,6)->nullable();
            $table->decimal('vat_amount',18,6)->nullable();
            $table->string('vat_type',20)->nullable();
            $table->tinyInteger('approval_layer')->default(1)->comment('current approval step');
            $table->string('comments')->nullable();
            $table->tinyInteger('status')->default(0)->nullable()->comment('0=pending,1=apprroved,2=partial approve,3=reject');
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
        Schema::dropIfExists('inventory_price_catalogue_details');
    }
}
