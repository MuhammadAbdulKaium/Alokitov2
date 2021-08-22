<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingMenuPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_menu_permission', function (Blueprint $table) {
            $table->integer('menu_id')->unsigned()->default(0);
            $table->integer('permission_id')->unsigned()->default(0);

            $table->foreign('menu_id')->references('id')->on('setting_menus')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['menu_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_menu_permission');
    }
}
