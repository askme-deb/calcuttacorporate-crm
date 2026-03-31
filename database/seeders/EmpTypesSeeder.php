<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('emptypes')->insert([
            [
                'name' => 'Article',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employee',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intern',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
