<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('designations')->insert([
            [
                'name' => 'Senior Developer',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Developer',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Project Manager',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UI/UX Developer',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Graphic Designer',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Graphic & Motion Designer',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Marketing Manager',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Team Lead',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SEO Analyst',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Project Co-ordinator',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HR Manager',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business Development Manager',
                'is_visible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
