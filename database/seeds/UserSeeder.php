<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i < 50; $i++) {
            DB::table('users')->insert([
                'name' => $faker->firstName,
                'created_at' => $faker->date(),
                'tel' => $faker->e164PhoneNumber,
                'status' => $faker->boolean(),
                'password' => $faker->password()
            ]);
        }
    }
}
