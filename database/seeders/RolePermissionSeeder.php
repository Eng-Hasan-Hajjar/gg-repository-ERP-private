<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $superAdmin = Role::where('name', 'super_admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $staff = Role::where('name', 'staff')->first();

        $allPermissions = Permission::all();

        $superAdmin->permissions()->sync($allPermissions->pluck('id'));
        $admin->permissions()->sync($allPermissions->pluck('id'));

        $staff->permissions()->sync(
            Permission::whereIn('name', [
                'view_students',
                'view_exams',
            ])->pluck('id')
        );
    }
}
