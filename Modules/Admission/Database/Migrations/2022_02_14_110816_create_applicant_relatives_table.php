<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantRelativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_relatives', function (Blueprint $table) {
            $table->id();
            $table->integer('applicant_id');
            $table->string('relation');
            $table->string('name');

            $table->string('bengali_name')->nullable();
            $table->integer('nationality')->nullable();
            $table->string('profession')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('organization')->nullable();
            $table->string('address')->nullable();
            $table->string('reference_contact')->nullable();
            $table->string('total_year_of_working')->nullable();
            $table->string('income_yearly')->nullable();
            $table->string('nid_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('passport')->nullable();
            $table->string('birth_certificateNo')->nullable();
            $table->string('driving_license')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('contact_phone');
            $table->string('contact_email')->nullable();
            $table->text('remarks')->nullable();


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
        Schema::dropIfExists('applicant_relatives');
    }
}
