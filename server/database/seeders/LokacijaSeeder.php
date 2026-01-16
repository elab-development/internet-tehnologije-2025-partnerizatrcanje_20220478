<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokacijaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nizLokacija = [
            ['naziv' => 'Beograd', 'long' => 20.457273, 'lat' => 44.787197],
            ['naziv' => 'Novi Sad', 'long' => 19.833549, 'lat' => 45.267136],
            ['naziv' => 'Niš', 'long' => 21.895758, 'lat' => 43.320902],
            ['naziv' => 'Kragujevac', 'long' => 20.922964, 'lat' => 44.012769],
            ['naziv' => 'Subotica', 'long' => 19.681531, 'lat' => 46.100000],
            ['naziv' => 'Kalemegdan', 'long' => 20.460000, 'lat' => 44.820000],
            ['naziv' => 'Ada Ciganlija', 'long' => 20.400000, 'lat' => 44.800000],
            ['naziv' => 'Tara', 'long' => 19.600000, 'lat' => 43.700000],
            ['naziv' => 'Kopaonik', 'long' => 20.800000, 'lat' => 43.200000],
            ['naziv' => 'Zlatibor', 'long' => 19.700000, 'lat' => 43.700000],
        ];

        foreach ($nizLokacija as $lokacija) {
            \App\Models\Lokacija::create($lokacija);
        }
    }
}
