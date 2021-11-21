<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryCustomerInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_customer_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable();
            $table->integer('type')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('image',100)->nullable();
            $table->string('gl_code',100)->nullable();
            $table->string('home_address',255)->nullable();
            $table->string('home_phone',20)->nullable();
            $table->string('home_city',255)->nullable();
            $table->string('home_mobile',20)->nullable();
            $table->string('home_state', 50)->nullable();
            $table->string('home_fax',50)->nullable();
            $table->string('home_zip_code',50)->nullable();
            $table->string('home_web',100)->nullable();
            $table->date('anniversary')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('business_company_name')->nullable();
            $table->string('business_job_title',255)->nullable();
            $table->string('business_address',255)->nullable();
            $table->string('business_department',255)->nullable();
            $table->string('business_state',255)->nullable();
            $table->string('business_zip_code',255)->nullable();
            $table->string('business_fax',50)->nullable();
            $table->string('business_country',100)->nullable();
            $table->string('business_web',255)->nullable();
            $table->string('business_phone',50)->nullable();
            $table->decimal('opening_balance',18,6)->nullable();
            $table->string('opening_balance_type',15)->nullable();
            $table->string('credit_priod',150)->nullable();
            $table->decimal('credit_limit',18,6)->nullable();
            $table->string('bill_tracking',15)->nullable();
            $table->integer('price_cate_id')->nullable();
            $table->string('maintaining_cost_center',15)->nullable();
            $table->string('commission_type',50)->nullable();
            $table->decimal('commission_value',18,6)->nullable();
            $table->string('bill_by_bill',255)->nullable();
            $table->tinyInteger('status')->nullable()->comment('1=Active, 0=Inactive');
            $table->softDeletes();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_customer_info');
    }
}
