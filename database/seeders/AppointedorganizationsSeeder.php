<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointedorganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('appointedorganizations')->insert([
            [
                'name' => 'Code of Dolphins',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'A.K. Saha & Co.',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Calcutta Corporate',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aditya Saha & Associates',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
