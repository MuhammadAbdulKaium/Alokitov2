<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsBudgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_budget', function (Blueprint $table) {
            $table->id();
            $table->string('fiscal_year', 100);
            $table->integer('account_id');
            $table->enum('account_type', ['group', 'ledger']);
            $table->enum('increase_by', ['debit', 'credit']);
            $table->double('amount',16,2);
            $table->integer('institute_id');
            $table->integer('campus_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('accounts_budget');
    }
}
