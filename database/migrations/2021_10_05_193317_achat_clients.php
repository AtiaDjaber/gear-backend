<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AchatClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achatclient', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained();
            $table->foreignId("facture_id")->constrained();
            $table->string("name");
            $table->decimal("price", 10, 2)->default("0");
            $table->decimal("priceRent", 10, 2)->default("0");
            $table->double("quantity")->default("0");
            $table->string("type");
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
