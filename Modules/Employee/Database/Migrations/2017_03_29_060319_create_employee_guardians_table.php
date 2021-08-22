<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeGuardiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_guardians', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emp_id')->unsigned()->default(0);
            $table->foreign('emp_id')->references('id')->on('employee_informations')->onDelete('cascade');
            $table->enum('title', array('Mr.', 'Mrs.', 'Ms.', 'Prof.', 'Dr.'))->nullable();
            $table->string('first_name')->default('Not Available');
            $table->string('last_name')->default('Not Available');
            $table->string('email')->default('Not Available');
            $table->string('mobile', 20)->default('Not Available');
            $table->string('phone', 20)->default('Not Available');
            $table->string('relation')->default('Not Available');
            $table->string('income')->default('Not Available');
            $table->string('qualification')->default('Not Available');
            $table->string('occupation')->default('Not Available');
            $table->string('home_address')->default('Not Available');
            $table->string('office_address')->default('Not Available');
            $table->boolean('is_emergency')->default(0);
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
        Schema::dropIfExists('employee_guardians');
    }
}
