<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ClientSeeder extends Seeder
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
            DB::table('clients')->insert([
                'name' => $faker->firstName . " " . $faker->lastName,
                'ancien' => "0",
                'montant' => "0",
                'created_at' => $faker->date(),
                'mobile' => $faker->e164PhoneNumber,
                'address' => $faker->address,
                'email' => $faker->unique()->email
            ]);
        }
    }
}
