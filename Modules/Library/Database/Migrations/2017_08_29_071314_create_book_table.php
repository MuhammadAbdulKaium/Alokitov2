<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('book_category_id');
            $table->integer('book_type');
            $table->string('subtitle');
            $table->string('isbn_no');
            $table->string('author');
            $table->integer('book_shelf_id');
            $table->integer('cup_board_shelf_id');
            $table->string('edition');
            $table->string('publisher');
            $table->integer('book_vendor_id');
            $table->double('book_cost');
            $table->string('copy');
            $table->mediumText('remark');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book');
    }
}
