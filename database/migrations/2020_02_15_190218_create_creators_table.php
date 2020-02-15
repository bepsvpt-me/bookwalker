<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 64);
            $table->boolean('author')->default(false);
            $table->boolean('writer')->default(false);
            $table->boolean('character_designer')->default(false);
            $table->boolean('illustrator')->default(false);
            $table->boolean('translator')->default(false);
            $table->boolean('cartoonist')->default(false);
            $table->integer('references')->unsigned()->default(0);

            $table->unique('name');

            $table->index('references');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creators');
    }
}
