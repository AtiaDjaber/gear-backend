<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Configs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->date("zakat", $precision = 0);
            $table->string("name_store")->nullable();
            $table->string("tel")->nullable();
            $table->string("address")->nullable();
            $table->string("email")->nullable();
            $table->string("remark")->nullable();
            $table->string("warning")->nullable();
            $table->string("printer")->nullable();
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
