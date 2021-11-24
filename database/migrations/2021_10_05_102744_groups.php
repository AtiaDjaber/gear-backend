<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Groups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId("subj_id")->constrained('subjs');
            $table->foreignId("teacher_id")->nullable()->constrained('teachers');
            $table->string("name");
            $table->decimal('price', 10, 2)->default('0');
            $table->integer('quotas')->default('4');
            $table->unique(['teacher_id', 'subj_id', 'name']);
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
