<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Pets
            'pets.manage',
            // ESA Requests
            'requests.create',
            'requests.view.own',
            'requests.view.assigned',
            'requests.view.all',
            'requests.approve',
            'requests.reject',
            // Payments
            'payments.create',
            'payments.view.own',
            'payments.view.all',
            'payments.refund',
            // Documents
            'documents.download.own',
            'documents.issue',
            // Admin
            'admin.dashboard.view',
            'admin.users.manage',
            'admin.roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
        }
        
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'sanctum']);
        $therapistRole = Role::firstOrCreate(['name' => 'therapist', 'guard_name' => 'sanctum']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);

        $userPermissions = [
            'pets.manage',
            'requests.create',
            'requests.view.own',
            'payments.create',
            'payments.view.own',
            'documents.download.own',
        ];
        
        $therapistPermissions = [
            'requests.view.assigned',
            'requests.approve',
            'requests.reject',
            'documents.issue',
        ];
        
        $adminPermissions = [
            'requests.view.all',
            'payments.view.all',
            'payments.refund',
            'admin.dashboard.view',
            'admin.users.manage',
            'admin.roles.manage',
        ];

        $userRole->givePermissionTo($userPermissions);
        $therapistRole->givePermissionTo($therapistPermissions);
        $adminRole->givePermissionTo($adminPermissions);

        // Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->assignRole($adminRole);

        // Normal User
        $normalUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal User',
                'password' => Hash::make('password'),
            ]
        );
        $normalUser->assignRole($userRole);
        
        // Therapist User
        $therapistUser = User::firstOrCreate(
            ['email' => 'therapist@example.com'],
            [
                'name' => 'Therapist User',
                'password' => Hash::make('password'),
            ]
        );
        $therapistUser->assignRole($therapistRole);
    }
}