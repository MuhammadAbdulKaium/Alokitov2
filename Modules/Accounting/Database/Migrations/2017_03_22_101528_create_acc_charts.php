<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Accounting\Entities\AccCharts;
class CreateAccCharts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_charts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chart_code');
            $table->string('chart_name');
            $table->integer('chart_parent')->nullable();
            $table->string('chart_type');
            $table->integer('company_id')->nullable();
            $table->integer('brunch_id')->nullable();
            $table->tinyInteger('cash_acc')->nullable();
            $table->tinyInteger('reconciliation')->nullable();
            $table->string('notes',1000)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('created_by')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $accHead = new AccCharts();
        $accHead->chart_code='ASSET';
        $accHead->chart_name='ASSET';
        $accHead->chart_type='G';
        $accHead->status=1;
        $accHead->save();


        $accHead = new AccCharts();
        $accHead->chart_code='LIABILITY';
        $accHead->chart_name='LIABILITY';
        $accHead->chart_type='G';
        $accHead->status=1;
        $accHead->save();


        $accHead = new AccCharts();
        $accHead->chart_code='INCOME';
        $accHead->chart_name='INCOME';
        $accHead->chart_type='G';
        $accHead->status=1;
        $accHead->save();


        $accHead = new AccCharts();
        $accHead->chart_code='EXPENSE';
        $accHead->chart_name='EXPENSE';
        $accHead->chart_type='G';
        $accHead->status=1;
        $accHead->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acc_charts');
    }
}
