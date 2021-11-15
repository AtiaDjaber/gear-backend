<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SubjSeeder extends Seeder
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
            DB::table('subjs')->insert([
                'name' => $faker->sentence($nbWords = 1, $variableNbWords = true),
                'grade' => $faker->randomElement($array = array('ثانوي', 'متوسط', 'ابتدائي ')),
                'level' => $faker->randomElement($array = array('سنة اولى', 'سنة ثالثة', 'سنة ثانية'))
            ]);
        }
    }
}
