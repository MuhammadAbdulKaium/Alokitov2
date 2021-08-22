<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsentFineSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absent_fine_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institution_id');
            $table->integer('campus_id');
            $table->integer('class');
            $table->integer('period');
            $table->float('amount');
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table ->unsignedBigInteger('created_by')-> nullable();
            $table ->unsignedBigInteger('updated_by')-> nullable();
            $table ->unsignedBigInteger('deleted_by')-> nullable();
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
