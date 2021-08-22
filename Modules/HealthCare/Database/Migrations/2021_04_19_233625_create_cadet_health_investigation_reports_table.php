<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetHealthInvestigationReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_health_investigation_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_type');
            $table->integer('patient_id');
            $table->integer('prescription_id');
            $table->integer('investigation_id');
            $table->longText('result')->nullable();
            $table->string('file')->nullable();
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('campus_id');
            $table->integer('institute_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadet_health_investigation_reports');
    }
}
