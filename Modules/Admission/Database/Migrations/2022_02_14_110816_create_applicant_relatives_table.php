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

            $table->string('bengaliName')->nullable();
            $table->integer('nationality')->nullable();
            $table->string('profession')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('organization')->nullable();
            $table->string('address')->nullable();
            $table->string('referenceContact')->nullable();
            $table->string('totalYearOfWorking')->nullable();
            $table->string('incomeYearly')->nullable();
            $table->string('nidNo')->nullable();
            $table->string('tinNo')->nullable();
            $table->string('passport')->nullable();
            $table->string('birthCertificateNo')->nullable();
            $table->string('drivingLicense')->nullable();
            $table->string('contactAddress')->nullable();
            $table->string('contactPhone');
            $table->string('contactEmail')->nullable();
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
