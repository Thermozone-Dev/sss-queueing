<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $configPermissions = [
        'view_any_appointment_configuration',
        'view_appointment_configuration',
        'update_appointment_configuration',
    ];

    private array $optionPermissions = [
        'view_any_appointment_option',
        'view_appointment_option',
        'create_appointment_option',
        'update_appointment_option',
    ];

    public function up(): void
    {
        foreach (array_merge($this->configPermissions, $this->optionPermissions) as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        foreach (['super_admin', 'head_office', 'branch_head'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName])->givePermissionTo($this->configPermissions);
        }

        foreach (['super_admin', 'head_office'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName])->givePermissionTo($this->optionPermissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        foreach (array_merge($this->configPermissions, $this->optionPermissions) as $permissionName) {
            Permission::whereName($permissionName)->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
