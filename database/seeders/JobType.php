<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('job_types')->insert([
            ['name' => 'Onetime','is_visible' => '1','created_at' => '2025-02-04 22:05:36','updated_at' => '2025-02-04 22:05:36'],
            ['name' => 'Recurring','is_visible' => '1','created_at' => '2025-02-04 22:05:44','updated_at' => '2025-02-04 22:05:44'],
        ]);
    }
}
