<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lead_status')->insert([
            array('name' => 'New','is_visible' => '1','created_at' => '2025-02-04 22:21:28','updated_at' => '2025-02-04 22:21:28'),
            array('name' => 'Contacted','is_visible' => '1','created_at' => '2025-02-04 22:21:38','updated_at' => '2025-02-04 22:21:38'),
            array('name' => 'Follow-Up','is_visible' => '1','created_at' => '2025-02-04 22:21:43','updated_at' => '2025-02-04 22:21:43'),
            array('name' => 'Interested','is_visible' => '1','created_at' => '2025-02-04 22:21:47','updated_at' => '2025-02-04 22:21:47'),
            array('name' => 'Qualified','is_visible' => '1','created_at' => '2025-02-04 22:21:51','updated_at' => '2025-02-04 22:21:51'),
            array('name' => 'Proposal Sent','is_visible' => '1','created_at' => '2025-02-04 22:21:56','updated_at' => '2025-02-04 22:21:56'),
            array('name' => 'Negotiation','is_visible' => '1','created_at' => '2025-02-04 22:22:00','updated_at' => '2025-02-04 22:22:00'),
            array('name' => 'Not Qualified','is_visible' => '1','created_at' => '2025-02-04 22:21:33','updated_at' => '2025-02-04 22:21:33'),
            array('name' => 'Converted','is_visible' => '1','created_at' => '2025-02-04 22:22:00','updated_at' => '2025-02-04 22:22:00'),
            array('name' => 'No Response','is_visible' => '1','created_at' => '2025-02-04 22:22:00','updated_at' => '2025-02-04 22:22:00'),
            array('name' => 'Junk','is_visible' => '1','created_at' => '2025-02-04 22:22:00','updated_at' => '2025-02-04 22:22:00'),

        ]);
    }
}
