<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LevelyearsSubjs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('levelyear_subj', function (Blueprint $table) {

            $table->foreignId("levelyear_id")->constrained();
            $table->foreignId("subj_id")->constrained();
            $table->unique(['subj_id', 'levelyear_id']);
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
