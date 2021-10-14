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
        Schema::create('std_group_teacher', function (Blueprint $table) {

            $table->id();
            $table->foreignId("student_id")->constrained()->nullable();
            $table->foreignId("group_teacher_id")->constrained('group_teacher')->nullable();
            $table->unique(['group_teacher_id', 'student_id']);
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
