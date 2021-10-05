<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Attendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId("std_sub_groups_id")->constrained();
            $table->date("date")->constrained();
            $table->dateTime("strart")->constrained();
            $table->dateTime("end")->constrained();
            $table->boolean("idPresent");
            $table->boolean("isJustified");
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
