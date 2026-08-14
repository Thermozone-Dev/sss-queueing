<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesTableSeeder extends Seeder
{
    /**
     * All available roles in the system
     */
    private const ROLES = [
        'super_admin',
        'head_office',
        'branch_head',
        'branch_staff',
        'member',
    ];

    /**
     * Resources and their role permissions
     * Customize role assignments per resource as needed
     */
    private const ROLE_PERMISSIONS = [
        'Employee' => ['super_admin', 'head_office', 'branch_head', 'branch_staff'],
        'Service' => ['super_admin', 'head_office', 'branch_head', 'branch_staff'],
        'Branch' => ['super_admin', 'head_office', 'branch_head'],
        'Appointment' => ['super_admin', 'head_office', 'branch_head', 'branch_staff', 'member'],
        'Station' => ['super_admin', 'head_office', 'branch_head', 'branch_staff'],
        'Department' => ['super_admin', 'head_office'],
        'User' => ['super_admin', 'head_office'],
        'Role' => ['super_admin'],
        'Exception' => ['super_admin'],
        'Activity' => ['super_admin'],
    ];

    public function run(): void
    {
        $this->createRoles();
        $this->assignPermissions();
    }

    /**
     * Create all roles if they don't exist
     */
    private function createRoles(): void
    {
        foreach (self::ROLES as $roleName) {
            Role::updateOrCreate(['name' => $roleName], ['name' => $roleName]);
        }
    }

    /**
     * Assign permissions to roles based on ROLE_PERMISSIONS configuration
     */
    private function assignPermissions(): void
    {
        foreach (self::ROLE_PERMISSIONS as $resource => $roles) {
            $permissions = Permission::where('name', 'like', '%' . strtolower($resource) . '%')->get();

            foreach ($permissions as $permission) {
                foreach ($roles as $roleName) {
                    $role = Role::where('name', $roleName)->first();
                    if ($role && !$role->hasPermissionTo($permission)) {
                        $role->givePermissionTo($permission);
                    }
                }
            }
        }

        $this->forgetCachedPermissions();
    }

    /**
     * Clear cached permissions
     */
    private function forgetCachedPermissions(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
