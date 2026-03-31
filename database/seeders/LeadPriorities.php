<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadPriorities extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lead_priorities')->insert([
            ['id' => '1','name' => 'Low','is_visible' => '1','created_at' => '2025-02-04 22:04:48','updated_at' => '2025-02-04 22:04:48'],
            ['id' => '2','name' => 'Medium','is_visible' => '1','created_at' => '2025-02-04 22:04:57','updated_at' => '2025-02-04 22:04:57'],
            ['id' => '3','name' => 'High','is_visible' => '1','created_at' => '2025-02-04 22:05:03','updated_at' => '2025-02-04 22:05:03']
        ]);

    }
}
