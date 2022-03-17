<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
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
            DB::table('Products')->insert([
                'name' => $faker->firstName,
                'priceRentHour' => $faker->numberBetween($min = 10, $max = 100),
                'priceRentDay' => $faker->numberBetween($min = 10, $max = 100),
                'price' => $faker->randomDigit,
                'quantity' => $faker->randomDigit,
                'type' => $faker->randomElement($array = array('ساعة', 'يوم'))
            ]);
        }
    }
}
