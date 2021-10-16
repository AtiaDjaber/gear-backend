<?php

use App\model\Expense;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i < 1000; $i++) {
            DB::table('expenses')->insert(['name' => $faker->sentence($nbWords = 3, $variableNbWords = true), 'price' => $faker->randomDigit, 'date' => $faker->date()]);
        }
        // Expense::factory()->count(200)->create()->each(function ($customer) {
        //     $customer->save();
        // });
    }
}
