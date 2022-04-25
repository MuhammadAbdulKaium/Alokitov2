<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignatoryConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signatory_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('campus_id');
            $table->integer('institute_id');
            $table->string('reportName')->require();
            $table->string('label')->require();
            $table->string('attatch')->nullable();
            $table->integer('empolyee_id');
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
        Schema::dropIfExists('signatory_configs');
    }
}
