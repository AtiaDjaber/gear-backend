<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ProductGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i < 20; $i++) {
            DB::table('std_group')->insert([
                'quotas' => $faker->numberBetween($min = 4, $max = 8),
                'group_id' => $faker->numberBetween($min = 1, $max = 99),
                'Product_id' => $faker->numberBetween($min = 1, $max = 99)
            ]);
        }
    }
}
