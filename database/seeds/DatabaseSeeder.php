<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(ExpenseSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(SubjSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(StudentGroupSeeder::class);
    }
}
