<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryNewRequisitionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_new_requisition_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('new_req_id');
            $table->integer('item_id');
            $table->decimal('req_qty',18,6)->nullable();
            $table->decimal('app_qty',18,6)->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('inventory_new_requisition_details');
    }
}
