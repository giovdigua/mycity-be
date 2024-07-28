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
    public function run(): void
    {
        $faker = Faker::create('it_IT');

        for ($i = 0; $i < 200; $i++) {
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

    private function generatePhoneNumber($faker): string
    {
        return '+39' . $faker->numerify('##########');
    }

    private function generateFiscalCode($faker): string
    {
        $fiscalCode = strtoupper($faker->randomLetter() . $faker->randomLetter() . $faker->randomLetter());
        $fiscalCode.=strtoupper($faker->randomLetter() . $faker->randomLetter() . $faker->randomLetter());
        $fiscalCode.=$faker->numerify('##');
        $fiscalCode.=strtoupper($faker->randomLetter());
        $fiscalCode.=$faker->numerify('##');
        $fiscalCode.=strtoupper($faker->randomLetter());
        $fiscalCode.=$faker->numerify('###');
        $fiscalCode.=strtoupper($faker->randomLetter());

        return $fiscalCode;
    }
}
