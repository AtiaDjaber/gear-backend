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
        $this->call(ClientSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(SubjSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(ProductGroupSeeder::class);
    }
}
