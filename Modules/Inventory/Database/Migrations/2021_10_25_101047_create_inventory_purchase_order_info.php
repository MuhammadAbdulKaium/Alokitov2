<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPurchaseOrderInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_order_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_no',100);
            $table->integer('voucher_int');
            $table->integer('voucher_config_id')->nullable();
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->integer('instruction_of')->nullable();
            $table->string('purchase_category',50)->nullable();
            $table->integer('vendor_id')->nullable();
            $table->string('comments')->nullable();
            $table->string('reference_type',100)->nullable();
            $table->tinyInteger('ref_use')->default(0)->nullable()->comment('0=No, 1= partial use, 3=used');
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
        Schema::dropIfExists('inventory_purchase_order_info');
    }
}
