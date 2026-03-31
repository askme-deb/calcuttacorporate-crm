<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('task_status')->insert([
            array('name' => 'Not Started', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:04', 'updated_at' => '2025-02-06 15:07:18'),
            array('name' => 'In Progress', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:13', 'updated_at' => '2025-02-06 15:07:24'),
            array('name' => 'On Hold', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Completed', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Cancelled', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'To Do', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:09', 'updated_at' => '2025-02-06 15:07:22'),
            array('name' => 'Pending Approval', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'In Review', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:09', 'updated_at' => '2025-02-06 15:07:22'),
            array('name' => 'Revisions Required', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Blocked', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Deferred', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'At Risk', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Overdue', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Escalated', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Reopened', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
            array('name' => 'Closed', 'is_visible' => '1', 'created_at' => '2025-02-06 15:07:16', 'updated_at' => '2025-02-06 15:07:27'),
        ]);
    }
}
