<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayPfRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_emp_pf', function (Blueprint $table){
            $table->increments('id');
            $table->date('approve_date')->nullable();
            $table->integer('pf_type_id')->references('id')->on('pay_salary_component')->onDelete('cascade');
            $table->enum('pf_ded_rule',(['','TR','EA','AR']));
            $table->enum('pf_ded_from',(['','B','G']));
            $table->enum('pf_ded_type',(['A','P']));
            $table->float('amt_val');
            $table->enum('comp_contribute_type',(['B','G','V','A']));
            $table->float('comp_contribute');
            $table->float('min_eligable_time');
            $table->date('deduction_date');
            $table->integer('company_id')->nullable();
            $table->integer('brunch_id')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('pay_emp_pf');
    }
}
