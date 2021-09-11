<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{

    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->string("address")->nullable();
            $table->string("tel");
            $table->string("status")->nullable();
            $table->string("photo")->nullable();
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->string("wilaya")->nullable();
            $table->string("commune")->nullable();
            $table->string("gris")->nullable();
            $table->boolean("online");
            $table->string("identity")->nullable();
            $table->string("registerNumber")->nullable();
            $table->double("rate")->default('0');
            $table->double("number_rates")->default('0');
            $table->double("sum_rates")->default('0');
            $table->string("token");

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
        Schema::dropIfExists('drivers');
    }
}
