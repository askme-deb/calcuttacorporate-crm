<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceTime extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('invoice_time')->insert([
            ['name' => 'Onetime','is_visible' => '1','created_at' => '2025-02-04 22:05:19','updated_at' => '2025-02-04 22:05:19'],
            ['name' => 'Monthly','is_visible' => '1','created_at' => '2025-02-04 22:05:27','updated_at' => '2025-02-04 22:05:27'],
            ['name' => 'Quaterly','is_visible' => '1','created_at' => '2025-02-04 22:05:27','updated_at' => '2025-02-04 22:05:27'],
            ['name' => 'Annually','is_visible' => '1','created_at' => '2025-02-04 22:05:27','updated_at' => '2025-02-04 22:05:27']
        ]);
    }
}
