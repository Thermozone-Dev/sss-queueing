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
        $staff_role = Role::firstOrCreate(['name' => 'staff']);

        DB::table('role_has_permissions')->where('role_id', $staff_role->id)->delete();

        $permissions = [
            'page_MyProfilePage',
            'widget_DashboardStats2',
            'assigned_station',
            'view_station',
            'view_any_station',

            'update_transaction',
            'create_transaction',
            'view_transaction',
            'view_any_transaction',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }


        $staff_role->givePermissionTo($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
