<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPriceCatalogueInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_price_catalogue_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalogue_uniq_id')->nullable()->comment('use it for all operation label');
            $table->integer('catalogue_ref_id')->nullable();
            $table->string('price_label')->nullable();
            $table->date('applicable_from');
            $table->tinyInteger('approval_level')->default(1)->comment('current approval step');
            $table->string('comments')->nullable();
            $table->tinyInteger('status')->default(0)->nullable()->comment('0=pending,1=apprroved,2=partial approve,3=reject');
            $table->integer('institute_id');
            $table->integer('campus_id');
            $table->softDeletes();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('valid')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_price_catalogue_info');
    }
}
