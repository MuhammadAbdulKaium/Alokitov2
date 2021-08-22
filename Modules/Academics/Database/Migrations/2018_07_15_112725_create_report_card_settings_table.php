<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportCardSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_card_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_border_color')->default(0);
            $table->string('border_color')->nullable();
            $table->string('border_type')->nullable();
            $table->string('border_width')->nullable();
            $table->boolean('is_label_color')->default(0);
            $table->string('label_bg_color')->nullable();
            $table->string('label_font_color')->nullable();
            $table->boolean('is_watermark')->default(0);
            $table->string('wm_url')->nullable();
            $table->string('wm_opacity')->nullable();
            $table->boolean('is_table_color')->default(0);
            $table->string('tbl_header_tr_bg_color')->nullable();
            $table->string('tbl_header_tr_font_color')->nullable();
            $table->string('tbl_even_tr_bg_color')->nullable();
            $table->string('tbl_even_tr_font_color')->nullable();
            $table->string('tbl_odd_tr_bg_color')->nullable();
            $table->string('tbl_odd_tr_font_color')->nullable();
            $table->string('tbl_opacity')->nullable();
            $table->boolean('parent_sign')->default(0);
            $table->boolean('teacher_sign')->default(0);
            $table->string('auth_sign')->nullable();
            $table->string('auth_name')->nullable();
            $table->integer('campus')->unsigned()->nullable();
            $table->foreign('campus')->references('id')->on('setting_campus')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('institute')->unsigned()->nullable();
            $table->foreign('institute')->references('id')->on('setting_institute')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('report_card_settings');
    }
}
