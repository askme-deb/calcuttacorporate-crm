<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadSector extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lead_sectors')->insert([
            ['id' => '1','name' => 'Medical','is_visible' => '1','created_at' => '2025-02-04 22:05:19','updated_at' => '2025-02-04 22:05:19'],
            ['id' => '2','name' => 'Education','is_visible' => '1','created_at' => '2025-02-04 22:05:27','updated_at' => '2025-02-04 22:05:27']
        ]);
    }
}
