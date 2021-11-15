<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i < 100; $i++) {
            DB::table('teachers')->insert([
                'firstname' => $faker->firstName, 'lastname' => $faker->lastName, 'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'), 'address' => $faker->address, 'email' => $faker->unique()->email
            ]);
        }
    }
}
