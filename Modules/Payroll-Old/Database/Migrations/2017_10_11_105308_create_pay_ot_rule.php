<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayOtRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_emp_ot', function (Blueprint $table) {
            $table->increments('id');
            $table->date('approve_date')->nullables();
            $table->date('effective_date');
            $table->integer('ot_type_id')->references('id')->on('pay_salary_component')->onDelete('cascade');
            $table->float('ot_rate');
            $table->time('ot_start');
            $table->time('ot_end');
            $table->string('min_ot')->nullable();
            $table->string('max_ot')->nullable();
            $table->string('ot_grace')->nullable();
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
        Schema::dropIfExists('pay_emp_ot');
    }
}
