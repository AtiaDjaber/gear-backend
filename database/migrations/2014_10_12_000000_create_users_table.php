<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string("tel");
            $table->string("type")->nullable();
            $table->string("status")->nullable();
            $table->string("photo")->nullable();
            $table->string("token")->nullable();
            $table->double('facture')->default('1');
            $table->double('client')->default('1');
            $table->double('expense')->default('1');
            $table->double('chart')->default('0');
            $table->double('user')->default('0');
            $table->string('password');
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
        Schema::dropIfExists('users');
    }
}
