<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [User::RANK_BEGGINNER, User::RANK_INTERMEDIATE, User::RANK_ADVANCED, User::RANK_PRO];
        $faker = \Faker\Factory::create();

        $adminUser = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'turina.sofija@gmail.com',
            'password' => bcrypt('admin'),
            'tipKorisnika' => 'admin',
            'rank' => $faker->randomElement($ranks),
        ]);

        for ($i = 1; $i <= 20; $i++) {
            \App\Models\User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password'),
                'tipKorisnika' => 'trkac',
                'rank' => $faker->randomElement($ranks),
            ]);
        }
    }
}
