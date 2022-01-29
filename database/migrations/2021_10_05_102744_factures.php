<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Factures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId("client_id")->constrained();
            $table->decimal('montant', 10, 2)->default('0');
            $table->decimal('pay', 10, 2)->default('0');
            $table->decimal('rest', 10, 2)->default('0');
            $table->decimal('remise', 10, 2)->default('0');
            $table->string("remark")->nullable();
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
