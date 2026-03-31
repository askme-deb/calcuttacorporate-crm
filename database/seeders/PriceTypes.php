<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceTypes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('price_types')->insert([
            ['name' => 'Hourly','is_visible' => '1','created_at' => '2025-02-04 22:05:19','updated_at' => '2025-02-04 22:05:19'],
            ['name' => 'Daily','is_visible' => '1','created_at' => '2025-02-04 22:05:27','updated_at' => '2025-02-04 22:05:27'],
            ['name' => 'Fix','is_visible' => '1','created_at' => '2025-02-04 22:05:27','updated_at' => '2025-02-04 22:05:27'],
        ]);
    }
}
