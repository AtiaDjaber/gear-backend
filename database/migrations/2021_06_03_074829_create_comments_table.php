<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('comments', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->bigInteger('orders_id')->unsigned();
//            $table->string('text');
//            $table->integer('star');
//            $table->timestamps();
//            $table->foreign('orders_id')
//                ->references('id')
//                ->on('orders')
//                ->onCascade('delete');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
