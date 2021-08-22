<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeShiftAssign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_assign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shift_id');
            $table->integer('emp_id');
            $table->date('effective_date_from');
            $table->date('effective_date_to');
            $table->integer('company_id');
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
        Schema::dropIfExists('shift_assign');
    }
}
