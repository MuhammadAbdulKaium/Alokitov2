<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatFeeInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_assign_id');
            $table->integer('institution_id');
            $table->string('invoice_num');
            $table->integer('campus_id');
            $table->integer('head_id');
            $table->integer('sub_head_id');
            $table->integer('student_id');
            $table->float('amount');
            $table->date('due_date');
            $table->date('start_date');
            $table->float('due_amount')->nullable();
            $table->float('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table ->unsignedBigInteger('created_by') -> nullable();
            $table ->unsignedBigInteger('updated_by') -> nullable();
            $table ->unsignedBigInteger('deleted_by') -> nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
