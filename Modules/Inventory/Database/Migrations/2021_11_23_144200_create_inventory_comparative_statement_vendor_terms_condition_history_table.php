<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryComparativeStatementVendorTermsConditionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_comparative_statement_vendor_terms_condition_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cs_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->longText('term_condition')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_comparative_statement_vendor_terms_condition_history');
    }
}
