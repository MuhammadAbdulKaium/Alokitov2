<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendBatchTable extends Migration
{

    public function up()
    {
//         Schema::table('batch', function($table)
//         {
//             $table->integer('section_id')->unsigned()->default(0);
//             $table->foreign('section_id')->references('id')->on('section')->onDelete('cascade');
//
////             $table->softDeletes();
//         });
    }

    public function down()
    {
    }
}
