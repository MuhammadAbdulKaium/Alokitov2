<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadetEmployeeDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadet_employee_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('document_type');
            $table->integer('qualification_type')->nullable();
            $table->integer('qualification_year')->nullable();
            $table->integer('qualification_name')->nullable();
            $table->integer('qualification_institute')->nullable();
            $table->integer('qualification_institute_address')->nullable();
            $table->integer('qualification_marks')->nullable();
            $table->integer('qualification_attachment')->nullable();
            $table->integer('experience_from_date')->nullable();
            $table->integer('experience_to_date')->nullable();
            $table->integer('experience_last_designation')->nullable();
            $table->integer('experience_organization_name')->nullable();
            $table->integer('experience_organization_address')->nullable();
            $table->integer('experience_organization_contact_person')->nullable();
            $table->integer('experience_organization_contact_email')->nullable();
            $table->integer('experience_organization_contact_number')->nullable();
            $table->integer('experience_attachment')->nullable();
            $table->integer('document_category')->nullable();
            $table->integer('document_no')->nullable();
            $table->integer('document_file')->nullable();
            $table->integer('other_info')->nullable();
            $table->integer('other_info_attachment')->nullable();
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
        Schema::dropIfExists('cadet_employee_documents');
    }
}
