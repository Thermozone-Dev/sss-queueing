<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        $permissions = [
            'view_branch',
            'view_any_branch',
            'create_branch',
            'update_branch',
            'restore_branch',
            'restore_any_branch',
            'replicate_branch',
            'reorder_branch',
            'delete_branch',
            'delete_any_branch',
            'force_delete_branch',
            'force_delete_any_branch',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $role->givePermissionTo($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'view_branch',
            'view_any_branch',
            'create_branch',
            'update_branch',
            'restore_branch',
            'restore_any_branch',
            'replicate_branch',
            'reorder_branch',
            'delete_branch',
            'delete_any_branch',
            'force_delete_branch',
            'force_delete_any_branch',
        ];

        Permission::whereIn('name', $permissions)->delete();

        Role::where('name', 'super_admin')->delete();

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
