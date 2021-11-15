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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId("std_group_id")->constrained('std_group');
            $table->string("groupName");
            // $table->double("groupId")->nullable();
            $table->foreignId("group_id")->constrained();
            $table->string("studentName");
            $table->string("studentBarcode");
            // $table->double("studentId")->nullable();
            $table->foreignId("student_id")->constrained();
            $table->string("subjName");
            // $table->double("subjId")->nullable();
            $table->foreignId("subj_id")->constrained();
            $table->string("teacherName");
            // $table->double("teacherId")->nullable();
            $table->foreignId("teacher_id")->constrained();
            $table->decimal('price', 10, 2)->default('0');
            $table->date("date");
            $table->dateTime("start");
            $table->dateTime("end");
            $table->boolean("isPresent");
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
