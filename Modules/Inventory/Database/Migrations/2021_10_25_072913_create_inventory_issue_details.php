<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryIssueDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_issue_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('issue_id');
            $table->integer('item_id');
            $table->decimal('issue_qty',18,6);
            $table->decimal('app_qty',18,6)->nullable();
            $table->decimal('rate',18,6)->nullable();
            $table->string('reference_type',100)->nullable();
            $table->integer('reference_id')->comment('voucher id');
            $table->integer('reference_details_id')->comment('voucher details id');
            $table->string('remarks')->nullable();
            $table->tinyInteger('approval_level')->default(1)->comment('current approval step');
            $table->tinyInteger('status')->default(0)->nullable()->comment('0=pending,1=issued,2=partial issued,3=reject');
            $table->tinyInteger('has_serial')->default(0)->nullable()->comment('1=yes,0=no');
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
        Schema::dropIfExists('inventory_issue_details');
    }
}
