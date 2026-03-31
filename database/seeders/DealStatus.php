<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DealStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('deal_status')->insert([
            array('name' => 'Open','is_visible' => '1','created_at' => '2025-02-04 23:16:15','updated_at' => '2025-02-04 23:16:15'),
            array('name' => 'Contacted','is_visible' => '1','created_at' => '2025-02-04 23:16:20','updated_at' => '2025-02-04 23:16:20'),
            array('name' => 'Qualification','is_visible' => '1','created_at' => '2025-02-04 23:16:25','updated_at' => '2025-02-04 23:16:25'),
            array('name' => 'Proposal Sent','is_visible' => '1','created_at' => '2025-02-04 23:16:30','updated_at' => '2025-02-04 23:16:30'),
            array('name' => 'Negotiation','is_visible' => '1','created_at' => '2025-02-04 23:16:35','updated_at' => '2025-02-04 23:16:35'),
            array('name' => 'Verbal Agreement','is_visible' => '1','created_at' => '2025-02-04 23:16:39','updated_at' => '2025-02-04 23:16:39'),
            array('name' => 'Closed Won','is_visible' => '1','created_at' => '2025-02-04 23:16:43','updated_at' => '2025-02-04 23:16:43'),
            array('name' => 'Closed Lost','is_visible' => '1','created_at' => '2025-02-04 23:16:48','updated_at' => '2025-02-04 23:16:48'),
            array('name' => 'Follow-Up Required','is_visible' => '1','created_at' => '2025-02-04 23:16:52','updated_at' => '2025-02-04 23:16:52'),
            array('name' => 'On Hold','is_visible' => '1','created_at' => '2025-02-04 23:16:52','updated_at' => '2025-02-04 23:16:52'),
            array('name' => 'No Response','is_visible' => '1','created_at' => '2025-02-04 23:16:52','updated_at' => '2025-02-04 23:16:52'),
        ]);
    }
}
