<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Portions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('group_id')->unsigned();
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onCascade('delete');
            $table->dateTime("start", $precision = 0);
            $table->dateTime("finish", $precision = 0);
            $table->date("date", $precision = 0);
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
