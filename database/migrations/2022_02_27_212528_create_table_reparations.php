<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReparations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reparations', function (Blueprint $table) {
            $table->id();
            // $table->foreignId("client_id")->constrained();
            $table->foreignId("product_id")->constrained();
            $table->foreignId("facture_id")->constrained();
            $table->double("quantity")->default("0");
            $table->string("remark")->nullable();
            $table->decimal("montant", 10, 2)->default("0");
            $table->date("date", $precision = 0);
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
        Schema::dropIfExists('table_reparations');
    }
}
