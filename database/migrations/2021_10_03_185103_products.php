<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->decimal("priceRentHour", 10, 2)->default("0");
            $table->decimal("priceRentDay", 10, 2)->default("0");
            $table->decimal("price", 10, 2)->default("0");
            $table->double("quantity")->default("0");
            $table->string("photo")->nullable();
            $table->string("type")->nullable();
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
        //
    }
}
