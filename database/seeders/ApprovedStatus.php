<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovedStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('approved_status')->insert([
            array('id' => '1', 'name' => 'Approved', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:50', 'updated_at' => '2025-02-06 15:07:50'),
            array('id' => '2', 'name' => 'Pending for Review', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:50', 'updated_at' => '2025-02-06 15:07:50'),
            array('id' => '3', 'name' => 'Reject', 'is_visible' => '1', 'created_at' => '2025-02-06 15:08:26', 'updated_at' => '2025-02-06 15:08:26')
        ]);

    }
}
