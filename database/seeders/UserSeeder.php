<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('it_IT');

        for ($i = 0; $i < 1000; $i++) {
            User::create([
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $this->generatePhoneNumber($faker),
                'fiscal_code' => $this->generateFiscalCode($faker),
                'date_of_birth' => $faker->date('Y-m-d', '2004-12-31'),
                'password' => Hash::make('123Stella'),
            ]);
        }
    }

    private function generatePhoneNumber($faker)
    {
        return '+39' . $faker->numerify('##########');
    }

    private function generateFiscalCode($faker)
    {
        $name = strtoupper($faker->randomLetter() . $faker->randomLetter() . $faker->randomLetter());
        $surname = strtoupper($faker->randomLetter() . $faker->randomLetter() . $faker->randomLetter());
        $year = $faker->numerify('##');
        $month = strtoupper($faker->randomLetter());
        $day = $faker->numerify('##');
        $code = strtoupper($faker->randomLetter() . $faker->randomLetter() . $faker->randomLetter());

        return $surname . $name . $year . $month . $day . $code;
    }
}
