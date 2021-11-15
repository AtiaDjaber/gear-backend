<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StdGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('std_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")->constrained();
            $table->foreignId("group_id")->constrained();
            $table->unique(['group_id', 'student_id']);
            $table->double('quotas')->default('4');

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
