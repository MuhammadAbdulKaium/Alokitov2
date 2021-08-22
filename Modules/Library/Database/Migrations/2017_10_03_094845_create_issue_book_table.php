<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_book', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asn_no');
            $table->integer('book_id');
            $table->string('isbn_no');
            $table->integer('holder_id');
            $table->integer('holder_type');
            $table->date('issue_date');
            $table->date('due_date');
            $table->integer('status')->nullable();
            $table->string(array('holder_id', 'holder_type','asn_no'));
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('issue_book');
    }
}
