<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("description")->nullable();
            $table->string("addressFrom")->nullable();
            $table->string("addressTo")->nullable();
            $table->double("fromLatitude");
            $table->double("fromLongutide");
            $table->double("toLatitude");
            $table->double("toLongutide");
            $table->string("status");
            $table->double("distance");
            $table->double("duration");
            $table->double("price")->nullable();
            $table->string("photo");
            $table->string("typeTransport");
            $table->double("rate")->nullable();
            $table->string("weight")->nullable();
            $table->timestamps();

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onCascade('delete');

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->index('driver_id')->nullable();
            $table->foreign('driver_id')
                ->nullable()
                ->references('id')
                ->on('drivers')
                ->onCascade('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
