<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaySalaryComponent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_salary_component', function (Blueprint $table){
            $table->increments('id');
            $table->string('name',100);
            $table->string('code',50)->nullable();
            $table->enum('type', ['A','D']);
            $table->enum('amount_type', ['B','OT','LN','PF'])->nullable();
            $table->float('fixed_amount',8,2)->nullable();
            $table->float('fixed_percent',4,2)->nullable();
            $table->enum('percent_base', ['B','G'])->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('brunch_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_salary_component');
    }
}
