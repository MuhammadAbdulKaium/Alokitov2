<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id')->nullable();
            $table->integer('entrytype_id');
            $table->bigInteger('number')->nullable();
            $table->date('date');
            $table->decimal('dr_total',25,2)->default(0.00);
            $table->decimal('cr_total',25,2)->default(0.00);
            $table->string('notes',500);
            $table->string('status')->nullable();
            $table->timestamps();
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
