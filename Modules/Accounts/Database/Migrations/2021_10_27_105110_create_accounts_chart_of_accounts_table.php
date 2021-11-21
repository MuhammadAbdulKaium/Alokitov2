<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsChartOfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code');
            $table->string('manual_account_code')->nullable();
            $table->string('account_name', 500);
            $table->integer('parent_id')->nullable();
            $table->enum('account_type', ['group', 'ledger']);
            $table->enum('increase_by', ['debit', 'credit']);
            $table->integer('layer');
            $table->integer('uid');
            $table->tinyInteger('valid')->default(1);
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
        Schema::dropIfExists('accounts_chart_of_accounts');
    }
}
