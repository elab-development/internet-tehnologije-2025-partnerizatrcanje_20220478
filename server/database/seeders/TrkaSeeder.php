<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrkaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //'naziv',
        //        'godina',
        //        'organizator',
        //        'kilometraza',
        //        'datum',
        //        'lokacija_id'

        $lokacije = \App\Models\Lokacija::all();
        $faker = \Faker\Factory::create();

        $organizatori = [
            'Atletski klub Beograd',
            'Trkački savez Srbije',
            'Sportski centar Novi Sad',
            'Atletski klub Niš',
            'Trkački klub Kragujevac'
        ];

        for ($i = 1; $i <= 30; $i++) {
            \App\Models\Trka::create([
                'naziv' => $faker->sentence(3),
                'godina' => $faker->year(),
                'organizator' => $faker->randomElement($organizatori),
                'kilometraza' => $faker->randomFloat(2, 5, 42),
                'datum' => $faker->date(),
                'lokacija_id' => $lokacije->random()->id,
            ]);
        }
    }
}
