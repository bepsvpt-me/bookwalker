<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('bookwalker_id')->unsigned();
            $table->string('name', 128);
            $table->string('slogan')->nullable();
            $table->text('description');
            $table->integer('price')->unsigned();
            $table->integer('pages')->unsigned()->default(0);
            $table->bigInteger('type_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('series_id')->unsigned()->nullable();
            $table->bigInteger('publisher_id')->unsigned();
            $table->dateTime('published_at');

            $table->unique('bookwalker_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
