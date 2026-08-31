<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            [
                'name' => 'View Dashboard',
                'slug' => 'dashboard.view',
                'group' => 'dashboard',
                'description' => 'View the organization dashboard.',
            ],
            [
                'name' => 'Manage Organization',
                'slug' => 'organization.manage',
                'group' => 'organization',
                'description' => 'Manage organization settings.',
            ],
            [
                'name' => 'View Members',
                'slug' => 'members.view',
                'group' => 'members',
                'description' => 'View organization members.',
            ],
            [
                'name' => 'Manage Members',
                'slug' => 'members.manage',
                'group' => 'members',
                'description' => 'Invite, update and remove organization members.',
            ],
            [
                'name' => 'View Billing',
                'slug' => 'billing.view',
                'group' => 'billing',
                'description' => 'View billing information.',
            ],
            [
                'name' => 'Manage Billing',
                'slug' => 'billing.manage',
                'group' => 'billing',
                'description' => 'Manage subscriptions and billing.',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        /*
        |--------------------------------------------------------------------------
        | System Roles
        |--------------------------------------------------------------------------
        */

        $roles = [
            [
                'name' => 'Organization Owner',
                'slug' => 'owner',
                'description' => 'Full control of the organization.',
                'is_system' => true,
            ],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Administrative access to the organization.',
                'is_system' => true,
            ],
            [
                'name' => 'Member',
                'slug' => 'member',
                'description' => 'Standard organization member.',
                'is_system' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Assign Permissions
        |--------------------------------------------------------------------------
        */

        $owner = Role::where('slug', 'owner')->firstOrFail();
        $admin = Role::where('slug', 'admin')->firstOrFail();
        $member = Role::where('slug', 'member')->firstOrFail();

        $owner->permissions()->sync(
            Permission::query()->pluck('id')
        );

        $admin->permissions()->sync(
            Permission::whereNotIn('slug', [
                'billing.manage',
            ])->pluck('id')
        );

        $member->permissions()->sync(
            Permission::whereIn('slug', [
                'dashboard.view',
                'members.view',
                'billing.view',
            ])->pluck('id')
        );
    }
}