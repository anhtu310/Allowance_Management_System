<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage_users',
            'manage_customers',
            'manage_requests',
            'view_customer_history',
            'manage_roles',
            'manage_permissions',
        ];

        foreach ($permissions as $name) {
            DB::table('permissions')->updateOrInsert(['name' => $name]);
        }

        $roleAdminId = DB::table('roles')->where('name', 'Admin')->value('id');
        $roleHrId = DB::table('roles')->where('name', 'HR')->value('id');

        $adminPermissions = DB::table('permissions')->whereIn('name', [
            'manage_users',
            'manage_customers',
            'manage_requests',
            'view_customer_history',
            'manage_roles',
            'manage_permissions',
        ])->pluck('id');

        $hrPermissions = DB::table('permissions')->whereIn('name', [
            'manage_customers',
            'manage_requests',
            'view_customer_history',
        ])->pluck('id');

        foreach ($adminPermissions as $permissionId) {
            DB::table('permission_role')->updateOrInsert([
                'role_id' => $roleAdminId,
                'permission_id' => $permissionId,
            ]);
        }

        foreach ($hrPermissions as $permissionId) {
            DB::table('permission_role')->updateOrInsert([
                'role_id' => $roleHrId,
                'permission_id' => $permissionId,
            ]);
        }
    }
}
