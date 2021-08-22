<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubheadfineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subheadfine', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institution_id');
            $table->integer('campus_id');
            $table->integer('sub_head_id');
            $table->string('type');
            $table->float('percentage_amount');
            $table->string('fine_type');
            $table->string('fine_increment_in')->nullable();
            $table->float('maximumfine')->nullable();
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
        Schema::dropIfExists('subheadfine');
    }
}
