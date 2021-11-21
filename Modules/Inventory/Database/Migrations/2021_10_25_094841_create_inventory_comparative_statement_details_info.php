<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryComparativeStatementDetailsInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_comparative_statement_details_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cs_id')->nullable();
            $table->integer('vendor_id')->nullable();
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
            $table->string('remarks')->nullable();
            $table->tinyInteger('ref_use')->default(0)->nullable()->comment('0=No,1= partial use, 3=used');
            $table->tinyInteger('approval_level')->default(1)->comment('current approval step');
            $table->tinyInteger('status')->default(0)->nullable()->comment('0=pending,1=apprroved,2=partial approve,3=reject,4=Issued,5=partial Issued');
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
        Schema::dropIfExists('inventory_comparative_statement_details_info');
    }
}
