<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryStructureDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_salary_structure_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('structure_id')->unsigned();
            $table->foreign('structure_id')->references('id')->on('pay_salary_structure')->onDelete('cascade');
            $table->integer('component_id')->unsigned();
            $table->foreign('component_id')->references('id')->on('pay_salary_component')->onDelete('cascade');
            $table->float('amount')->nullable();
            $table->enum('percent',(['','P']))->nullable();
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
        Schema::dropIfExists('pay_salary_structure_detail');
    }
}
