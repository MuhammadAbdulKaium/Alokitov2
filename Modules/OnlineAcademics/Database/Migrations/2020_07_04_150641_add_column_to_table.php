<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_class_schedule', function (Blueprint $table) {
			$table->string('class_start_time')->nullable()->after('class_routine_time');
			$table->string('class_end_time')->nullable()->after('class_start_time');
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
			$table->dropColumn('class_start_time');
			$table->dropColumn('class_end_time');
        });
    }
}
