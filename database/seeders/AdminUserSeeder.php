<?php

namespace Database\Seeders;

use App\Models\Lms\SourceQuery;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'application_setting_view',
            'user_add', 'user_edit', 'user_view', 'user_delete',
            'role_add', 'role_edit', 'role_view', 'role_delete', 'role_assign_permission',
            'lms_dashboard', 'lms_my_leads', 'lms_all_leads',
            'lead_add', 'lead_edit', 'lead_view', 'lead_delete',
            'bs_add', 'bs_edit', 'bs_view', 'bs_delete',
            'country_add', 'country_edit', 'country_view', 'country_delete',
            'city_add', 'city_edit', 'city_view', 'city_delete',
            'source_query_add', 'source_query_edit', 'source_query_view', 'source_query_delete',
            'service_add', 'service_edit', 'service_view', 'service_delete',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ]);
        }

        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);
        $adminRole->syncPermissions(Permission::where('guard_name', 'web')->get());

        Role::firstOrCreate(['name' => 'SPO', 'guard_name' => 'web']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123456'),
                'status' => 1,
                'role_id' => $adminRole->id,
                'mobile' => '971500000000',
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles([$adminRole->name]);

        // Website / Facebook sources for open-channel lead alerts
        SourceQuery::unguard();
        SourceQuery::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Website / Free Quote',
                'created_by' => $admin->id,
            ]
        );
        SourceQuery::firstOrCreate(
            ['name' => 'Facebook'],
            ['created_by' => $admin->id]
        );
        SourceQuery::reguard();
    }
}
