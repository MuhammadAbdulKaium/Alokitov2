<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_entries_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entries_id');
            $table->integer('ledger_id');
            $table->decimal('amount',25,2);
            $table->char('dc',1);
            $table->date('reconciliation_date')->nullable();
            $table->string('narration',500);
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
