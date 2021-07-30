<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferTable extends Migration
{

    public function up()
    {
        Schema::create('offer', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("price");
            $table->timestamps();

            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')
                ->references('id')
                ->on('drivers')
                ->onCascade('delete');

            $table->bigInteger('orders_id')->unsigned();
            $table->foreign('orders_id')
                ->references('id')
                ->on('orders')
                ->onCascade('delete');
        });
    }


    public function down()
    {
        Schema::dropIfExists('offer');
    }
}
