<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UcesceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::where('tipKorisnika', 'trkac')->get();
        $trke = \App\Models\Trka::all();
        $faker = \Faker\Factory::create();

        foreach ($users as $user) {
            $brojTrka = rand(1, 5);
            $odabraneTrke = $trke->random($brojTrka);

            foreach ($odabraneTrke as $trka) {
                \App\Models\Ucesce::create([
                    'trka_id' => $trka->id,
                    'user_id' => $user->id,
                    'vreme' => $faker->randomDigit(),
                ]);
            }
        }
    }
}
