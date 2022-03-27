<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeInformationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_information_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('employee_id');
            $table->enum('operation', array('CREATE','UPDATE','DELETE'))->nullable();
            $table->enum('value_type', array('role','title','first_name','middle_name','last_name','employee_no','position_serial','alias','gender','dob','doj','dor','department','designation','category','email','phone','alt_mobile','religion','blood_group','birth_place','marital_status','nationality','experience_year','experience_month','present_address','permanent_address'))->nullable();
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->integer('institute_id')->nullable();
            $table->integer('campus_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('employee_information_histories');
    }
}
