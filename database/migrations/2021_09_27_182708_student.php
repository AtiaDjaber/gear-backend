<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Student extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string("firstname");
            $table->string("lastname");
            $table->date("birthday")->nullable();
            $table->date("barcode")->nullable();
            $table->string("mobile")->nullable();
            $table->string("email")->unique()->nullable();
            $table->string("address")->unique()->nullable();
            $table->string("parent")->nullable();
            $table->string("parentMobile")->nullable();
            $table->string("grade")->nullable();
            $table->string("yearScholaire")->nullable();
            $table->string("photo")->nullable();
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
