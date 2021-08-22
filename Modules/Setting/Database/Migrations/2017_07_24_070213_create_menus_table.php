<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->unsigned()->default(0);
            $table->integer('sub_module_id')->unsigned()->default(0);
            $table->string('name')->default('not set');
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('setting_menus');
    }
}
