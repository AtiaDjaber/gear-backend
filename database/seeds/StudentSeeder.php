<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
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
            DB::table('students')->insert([
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'barcode' => $faker->unique()->numberBetween($min = 1000000000000, $max = 9999999999999),
                'birthday' => $faker->date(
                    $format = 'Y-m-d',
                    $max = 'now'
                ),
                'address' => $faker->address,
                'email' => $faker->unique()->email
            ]);
        }
    }
}
