<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateStudentAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_id')->unsigned()->default(0);
            $table->foreign('std_id')->references('id')->on('student_informations')->onDelete('cascade');
            $table->integer('doc_id')->unsigned()->default(0);
            $table->foreign('doc_id')->references('id')->on('contents')->onDelete('cascade');
            $table->string('doc_type')->default("Not available");
            $table->string('doc_details', 500)->default("Not available");
            $table->date('doc_submited_at')->nullable();
            $table->boolean('doc_status')->default(0);
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
        Schema::dropIfExists('student_attachments');
    }
}
