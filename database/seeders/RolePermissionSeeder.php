<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = DB::table('roles')->insertGetId([
            'name' => 'admin',
            'description' => 'Administrator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $editorRole = DB::table('roles')->insertGetId([
            'name' => 'editor',
            'description' => 'Editor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create permissions
        $permissions = [
            'view_dashboard',
            'create_news',
            'edit_news',
            'delete_news',
            'view_contents',
            'create_contents',
            'edit_contents',
            'delete_contents',
            'manage_users',
            'manage_categories',
            'view_moderation',
            'view_analytics',
            'view_comments',
        ];

        $permissionIds = [];
        foreach ($permissions as $permission) {
            $permissionIds[] = DB::table('permissions')->insertGetId([
                'name' => $permission,
                'description' => ucfirst(str_replace('_', ' ', $permission)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign all permissions to admin role
        foreach ($permissionIds as $permId) {
            DB::table('role_permissions')->insert([
                'role_id' => $adminRole,
                'permission_id' => $permId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}