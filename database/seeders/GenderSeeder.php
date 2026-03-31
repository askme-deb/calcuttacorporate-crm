<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genders')->insert([
            [
                'name' => 'Male',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Female',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
