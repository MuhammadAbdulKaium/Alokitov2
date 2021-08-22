<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatFeeWaiverassign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_waiverassign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institution_id');
            $table->integer('campus_id');
            $table->integer('head_id');
            $table->integer('sub_head_id');
            $table->integer('waiver_type');
            $table->integer('student_id');
            $table->integer('amount_percentage');
            $table->float('amount');
            $table->date('date');
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
