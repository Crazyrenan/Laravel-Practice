<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProjectsTableSeeder extends Seeder
{
    public function run()
    {
        // Reset the table and IDs
        DB::table('projects')->truncate();

        $faker = Faker::create();

        foreach (range(1, 5) as $i) {
            DB::table('projects')->insert([
                'id' => $i,
                'name' => $faker->catchPhrase,
                'description' => $faker->sentence(8),
                'status' => $faker->randomElement(['active', 'inactive']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}


