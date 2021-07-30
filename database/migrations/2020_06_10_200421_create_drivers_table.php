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
            $table->string("name");
            $table->string("email");
            $table->string("address");
            $table->string("tel");
            $table->string("status");
            $table->string("photo");
            $table->string("latitude");
            $table->string("longitude");
            $table->string("wilaya");
            $table->string("commune");
            $table->string("gris");
            $table->boolean("online");
            $table->string("identity");
            $table->string("registerNumber");
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
