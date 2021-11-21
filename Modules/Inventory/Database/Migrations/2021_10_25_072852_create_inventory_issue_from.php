<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryIssueFrom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_issue_from', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_no',100);
            $table->integer('voucher_int');
            $table->integer('voucher_config_id')->nullable();
            $table->date('date');
            $table->integer('store_id')->nullable();
            $table->integer('issue_to')->nullable();
            $table->string('reference_type', 20)->nullable();
            $table->string('comments')->nullable();
            $table->tinyInteger('status')->default(0)->nullable()->comment('0=pending,1=issued,2=partial issued,3=reject');
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
        Schema::dropIfExists('inventory_issue_from');
    }
}
