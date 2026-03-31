<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Define the default permissions
                $permissions = [
                    array('name' => 'Create Role','group_name' => 'Roles','guard_name' => 'web','created_at' => '2025-02-26 00:44:19','updated_at' => '2025-02-26 00:44:19'),
                    array('name' => 'View Role','group_name' => 'Roles','guard_name' => 'web','created_at' => '2025-02-26 00:44:19','updated_at' => '2025-02-26 00:44:19'),
                    array('name' => 'Edit Role','group_name' => 'Roles','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Role','group_name' => 'Roles','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Permission','group_name' => 'Permissions','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Permission','group_name' => 'Permissions','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Permission','group_name' => 'Permissions','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Permission','group_name' => 'Permissions','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Assign Permission','group_name' => 'Permissions','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create User','group_name' => 'Users','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View User','group_name' => 'Users','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit User','group_name' => 'Users','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete User','group_name' => 'Users','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Employee','group_name' => 'Employees','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Employee','group_name' => 'Employees','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Employee','group_name' => 'Employees','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Employee','group_name' => 'Employees','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Attendance','group_name' => 'Attendance','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Attendance','group_name' => 'Attendance','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Attendance','group_name' => 'Attendance','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Attendance','group_name' => 'Attendance','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Leave','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Leave','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Leave','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Leave','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Leave Type','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Leave Type','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Leave Type','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Leave Type','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Holiday','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Holiday','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Holiday','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Holiday','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Lead','group_name' => 'Leads','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Lead','group_name' => 'Leads','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Lead','group_name' => 'Leads','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Lead','group_name' => 'Leads','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Convert Lead','group_name' => 'Leads','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Convert Lead List','group_name' => 'Leads','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Deal','group_name' => 'Deals','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Deal','group_name' => 'Deals','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Deal','group_name' => 'Deals','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Deal','group_name' => 'Deals','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Convert Deal','group_name' => 'Deals','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Closed Deal List','group_name' => 'Deals','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Project','group_name' => 'Projects','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Projects','group_name' => 'Projects','guard_name' => 'web','created_at' => '2025-02-25 19:14:20','updated_at' => '2025-02-25 19:14:20'),
                    array('name' => 'View Project','group_name' => 'Projects','guard_name' => 'web','created_at' => '2025-02-26 23:48:38','updated_at' => '2025-02-26 23:48:38'),
                    array('name' => 'Edit Project','group_name' => 'Projects','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Project','group_name' => 'Projects','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Asign Project','group_name' => 'Projects','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Price','group_name' => 'Projects','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Client Deadline','group_name' => 'Projects','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Task','group_name' => 'Tasks','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Task','group_name' => 'Tasks','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Task','group_name' => 'Tasks','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Task','group_name' => 'Tasks','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Client','group_name' => 'Clients','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Client','group_name' => 'Clients','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Client','group_name' => 'Clients','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Client','group_name' => 'Clients','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Master Data','group_name' => 'Master Data','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'View Master Data','group_name' => 'Master Data','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Edit Master Data','group_name' => 'Master Data','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Delete Master Data','group_name' => 'Master Data','guard_name' => 'web','created_at' => '2025-02-26 00:44:20','updated_at' => '2025-02-26 00:44:20'),
                    array('name' => 'Create Leave for Others','group_name' => 'Leave','guard_name' => 'web','created_at' => '2025-02-26 23:33:31','updated_at' => '2025-02-26 23:33:31'),
                    array('name' => 'Manage Accounts','group_name' => 'Accounts','guard_name' => 'web','created_at' => '2025-02-26 23:41:51','updated_at' => '2025-02-26 23:41:51'),
                    array('name' => 'Manage Settings','group_name' => 'Settings','guard_name' => 'web','created_at' => '2025-02-26 23:45:17','updated_at' => '2025-02-26 23:45:17'),
                    array('name' => 'view all logs','group_name' => 'Dashboard','guard_name' => 'web','created_at' => '2025-02-26 23:48:38','updated_at' => '2025-02-26 23:48:38'),
                ];

                // Create the permissions
                foreach ($permissions as $permission) {
                    Permission::firstOrCreate(
                        ['name' => $permission['name']],
                        ['group_name' => $permission['group_name']]
                    );
                }

    }
}
