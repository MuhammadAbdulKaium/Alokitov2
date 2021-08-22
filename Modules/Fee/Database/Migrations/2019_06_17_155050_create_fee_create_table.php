<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_create', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institution_id');
            $table->integer('campus_id');
            $table->integer('head_id');
            $table->longText('subheadlist');
            $table->longText('fundlist');
            $table->string('status')->nullable();
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
        Schema::dropIfExists('fee_create');
    }
}
