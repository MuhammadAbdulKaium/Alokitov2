<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_configuration', function (Blueprint $table) {
            $table->id();
            $table->integer('order_no')->nullable();
            $table->string('label_name', 20)->nullable();
            $table->string('display_label_name', 50)->nullable();
            $table->string('particular', 100)->nullable();
            $table->string('particular_name', 100)->nullable();
            $table->string('particular_code', 200)->nullable();
            $table->integer('particular_id')->nullable();
            $table->enum('account_type', ['group', 'ledger'])->nullable();
            $table->enum('increase_by', ['debit', 'credit'])->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('accounts_configuration');
    }
}
