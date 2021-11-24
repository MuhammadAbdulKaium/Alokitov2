<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryComparativeStatementVendorHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_comparative_statement_vendor_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cs_id')->nullable();
            $table->integer('cs_details_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('gl_code')->nullable();
            $table->decimal('credit_limit',18,6)->nullable();
            $table->string('credit_priod')->nullable();
            $table->integer('item_id')->nullable();
            $table->decimal('qty',18,6)->nullable();
            $table->decimal('rate',18,6)->nullable();
            $table->decimal('amount',18,6)->nullable();
            $table->decimal('discount',18,6)->nullable();
            $table->decimal('vat_per',18,6)->nullable();
            $table->string('vat_type',15)->nullable();
            $table->decimal('vat_amount',18,6)->nullable();
            $table->decimal('net_amount',18,6)->nullable();
            $table->string('reference_type',50)->nullable();
            $table->integer('reference_id')->nullable();
            $table->integer('reference_details_id')->nullable();
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
        Schema::dropIfExists('inventory_comparative_statement_vendor_history');
    }
}
