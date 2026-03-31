<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Leadsource extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lead_sources')->insert([
            ['id' => '1','name' => 'Justdial','is_visible' => '1','created_at' => '2025-02-04 22:05:36','updated_at' => '2025-02-04 22:05:36'],
            ['id' => '2','name' => 'Upwork','is_visible' => '1','created_at' => '2025-02-04 22:05:44','updated_at' => '2025-02-04 22:05:44'],
            ['id' => '3','name' => 'Freelancer','is_visible' => '1','created_at' => '2025-02-04 22:05:51','updated_at' => '2025-02-04 22:05:51'],
            ['id' => '4','name' => 'Facebook','is_visible' => '1','created_at' => '2025-02-04 22:05:51','updated_at' => '2025-02-04 22:05:51'],
            ['id' => '5','name' => 'Instagram','is_visible' => '1','created_at' => '2025-02-04 22:05:51','updated_at' => '2025-02-04 22:05:51'],
            ['id' => '6','name' => 'Youtube Promotion','is_visible' => '1','created_at' => '2025-02-04 22:05:51','updated_at' => '2025-02-04 22:05:51'],
            ['id' => '7','name' => 'Google','is_visible' => '1','created_at' => '2025-02-04 22:05:51','updated_at' => '2025-02-04 22:05:51'],
            ['id' => '8','name' => 'Reference From Client','is_visible' => '1','created_at' => '2025-02-04 22:05:51','updated_at' => '2025-02-04 22:05:51'],
            ['id' => '9','name' => 'Personal Reference','is_visible' => '1','created_at' => '2025-02-04 22:05:51','updated_at' => '2025-02-04 22:05:51'],
        ]);
    }
}
