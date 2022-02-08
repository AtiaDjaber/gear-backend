<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker::create();
        // for ($i = 1; $i < 100; $i++) {
        //     DB::table('groups')->insert([
        //         'name' => $faker->sentence($nbWords = 1, $variableNbWords = true),
        //         'subj_id' => $faker->numberBetween($min = 1, $max = 99),
        //         'Client_id' => $faker->numberBetween($min = 1, $max = 99)
        //     ]);
        // }
    }
}
