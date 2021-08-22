<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_class_schedule', function (Blueprint $table) {
			$table->string('class_teacher_status')->nullable()->after('class_teacher_remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_class_schedule', function (Blueprint $table) {
			$table->dropColumn('class_teacher_status');
        });
    }
}
