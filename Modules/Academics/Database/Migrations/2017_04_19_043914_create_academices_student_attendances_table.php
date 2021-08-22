<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicesStudentAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academices_student_attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsingned()->default(0);
            $table->integer('attendacnce_type')->unsingned()->default(0);
            $table->integer('teacher_id')->unsingned()->default(0);
            $table->date('attendance_date');
            $table->string('remarks')->default('not set');
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
        Schema::dropIfExists('academices_student_attendances');
    }
}
