<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Clients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("mobile")->nullable();
            $table->decimal('montant', 10, 2)->default('0');
            $table->decimal('ancien', 10, 2)->default('0');
            $table->string("email")->unique()->nullable();
            $table->string("address")->nullable();
            $table->string("photo")->nullable();
            $table->boolean("status")->default("1");
            $table->timestamps();
        });
        //
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
