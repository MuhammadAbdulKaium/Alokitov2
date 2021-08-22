<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAttendanceFineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_attendance_fine', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ins_id');
            $table->integer('academic_year');
            $table->integer('campus_id');
            $table->integer('std_id');
            $table->date('date')->nullable();
            $table->double('fine_amount')->nullable();
            $table->integer('is_read')->default(0);
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('student_attendance_fine');
    }
}
