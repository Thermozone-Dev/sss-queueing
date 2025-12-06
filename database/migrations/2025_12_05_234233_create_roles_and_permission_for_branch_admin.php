<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'page_MyProfilePage',
            'widget_DashboardStats2',

            'update_station',
            'create_station',
            'view_station',
            'view_any_station',

            'update_transaction',
            'create_transaction',
            'view_transaction',
            'view_any_transaction',

            'create_user',
            'update_user',
            'view_user',
            'view_any_user',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $staff_role = Role::firstOrCreate(['name' => 'branch_admin']);

        $staff_role->givePermissionTo($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles_and_permission_for_branch_admin');
    }
};
