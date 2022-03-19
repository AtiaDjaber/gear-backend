<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained();
            $table->foreignId("facture_id")->constrained();
            $table->foreignId("client_id")->constrained();
            $table->string("name");
            $table->decimal("total", 10, 2)->default("0");
            $table->decimal("priceRentHour", 10, 2)->default("0");
            $table->decimal("priceRentDay", 10, 2)->default("0");
            $table->double("quantity")->default("0");
            $table->double("duration")->default("0");
            $table->string("type");
            // $table->string("type_table")->default("kira");
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
        //
    }
}
