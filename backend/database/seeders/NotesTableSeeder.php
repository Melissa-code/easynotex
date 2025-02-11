<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all the users & categories
        $users = DB::table('users')->pluck('id');
        $categories = DB::table('categories')->pluck('id');

        for ($i = 0; $i < 6; $i++) {
            DB::table('notes')->insert([
                'user_id' => $faker->randomElement($users),
                'category_id' => $faker->randomElement($categories),
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'isFavorite' => $faker->boolean,
                'image' => $faker->imageUrl,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
