<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KomentarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $postovi = \App\Models\Post::all();
        $faker = \Faker\Factory::create();
        $users = User::all();

        foreach ($postovi as $post) {
            $brojKomentara = rand(1, 5);

            for ($i = 0; $i < $brojKomentara; $i++) {
                \App\Models\Komentar::create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'komentar' => $faker->sentence(),
                    'datum_komentara' => $faker->dateTimeBetween($post->datum, 'now'),
                    'ocena' => $faker->numberBetween(1, 5),
                ]);
            }
        }

    }
}
