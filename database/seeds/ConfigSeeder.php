<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;


class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('configs')->insert([
            'name_store' => $faker->firstName,
            'zakat' => $faker->date(),
            'created_at' => $faker->date(),
            'tel' => $faker->e164PhoneNumber,
            'address' => $faker->address,
            'email' => $faker->unique()->email
        ]);
    }
}
