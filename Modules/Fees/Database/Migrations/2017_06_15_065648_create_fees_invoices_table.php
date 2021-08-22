<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institution_id');
            $table->integer('campus_id');
            $table->integer('fees_id');
            $table->integer('payer_id');
            $table->integer('payer_type')->nullable();
            $table->double('waiver_fees')->nullable();
            $table->integer('waiver_type')->nullable();
            $table->string('payer_status')->nullable();
            $table->string('inform_status')->nullable();
            $table->string('invoice_status')->nullable();
            $table->string('wf_status')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fees_invoices');
    }
}
