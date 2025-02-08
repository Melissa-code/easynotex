<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Notes personnelles', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Travail-Études', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finances-Administratif', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Créativité-Loisirs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Santé-Bien-être', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Voyages-Sorties', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
