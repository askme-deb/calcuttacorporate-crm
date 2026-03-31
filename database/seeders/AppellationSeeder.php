<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppellationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('appellations')->insert([
            [
                'name' => 'Dr',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Janab',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mr',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mrs',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], 
            [
                'name' => 'Ms',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shri',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
