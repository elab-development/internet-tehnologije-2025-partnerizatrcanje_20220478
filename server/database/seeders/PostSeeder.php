<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ucesca = \App\Models\Ucesce::all();
        $faker = \Faker\Factory::create();

        foreach ($ucesca as $ucesce) {
            $brojPostova = rand(1, 3);

            for ($i = 0; $i < $brojPostova; $i++) {
                \App\Models\Post::create([
                    'ucesce_id' => $ucesce->id,
                    'sadrzaj' => $faker->paragraph(),
                    'datum_objave' => $faker->dateTimeBetween($ucesce->trka->datum, 'now'),
                ]);
            }
        }
    }
}
