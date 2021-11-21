<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryComparativeStatementInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_comparative_statement_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_no',100)->nullable();
            $table->integer('voucher_int')->nullable();
            $table->integer('voucher_config_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('instruction_of')->nullable();
            $table->string('comments')->nullable();
            $table->tinyInteger('status')->default(0)->nullable()->comment('0=pending,1=apprroved,2=partial approve,3=reject,4=Issued,5=partial Issued');
            $table->tinyInteger('ref_use')->default(0)->nullable()->comment('0=No,1=Yes');
            $table->string('reference_type', 50)->nullable();
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
        Schema::dropIfExists('inventory_comparative_statement_info');
    }
}
