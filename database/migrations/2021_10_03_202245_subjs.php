<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Subjs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjs', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("level");
            $table->string("grade");
            $table->string("photo")->nullable();
            $table->unique(['name', 'level', 'grade']);

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
