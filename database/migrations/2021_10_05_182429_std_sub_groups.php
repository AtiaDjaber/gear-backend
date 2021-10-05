<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StdSubGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('std_sub_groups', function (Blueprint $table) {

            $table->id();
            $table->foreignId("student_id")->constrained()->nullable();
            $table->foreignId("group_id")->constrained()->nullable();
            $table->foreignId("levelyear_subj_id")->constrained('levelyear_subj')->nullable();
            $table->unique(['group_id', 'student_id', 'levelyear_subj_id']);
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
