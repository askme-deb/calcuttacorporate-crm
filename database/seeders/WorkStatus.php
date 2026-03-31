<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_status')->insert([
            array('name' => 'Not Started', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:04', 'updated_at' => '2025-02-06 15:07:18'),
            array('name' => 'Planning', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:09', 'updated_at' => '2025-02-06 15:07:22'),
            array('name' => 'Requirements Gathering', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:09', 'updated_at' => '2025-02-06 15:07:22'),
            array('name' => 'Resources Allocated', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'In Progress', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:13', 'updated_at' => '2025-02-06 15:07:24'),
            array('name' => 'On Hold', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Pending Approval', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Revisions Required', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Testing/Review', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Completed', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Cancelled', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Archived', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Client Review Pending', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Delayed', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27')
        ]);

    }
}
