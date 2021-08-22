<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreagteStudentTestimonialResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_testimonial_result', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_id');
            $table->integer('result_type');
            $table->string('gpa');
            $table->string('gpa_details');
            $table->string('reg_no');
            $table->string('year');
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
        //
    }
}
