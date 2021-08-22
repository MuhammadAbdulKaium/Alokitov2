<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BillingInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institute_billing_info', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('deposited', 8, 2)->nullable();
            $table->integer('rate_per_student')->nullable();
            $table->integer('total_amount')->nullable();
            $table->integer('accepted_amount')->nullable();
            $table->decimal('rate_per_sms', 8, 2)->nullable();
            $table->decimal('total_sms_price', 8, 2)->nullable();
            $table->decimal('accepted_sms_price', 8, 2)->nullable();
            $table->year('year')->dafault(date("Y"));
            $table->string('month', 100)->dafault(date("F"));
            $table->boolean('status')->default(1);
            $table->integer('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('institute_billing_info');
    }
}
