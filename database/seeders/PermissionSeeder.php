<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Users
            ['name' => 'view_users', 'label' => 'عرض المستخدمين', 'module' => 'users'],
            ['name' => 'create_users', 'label' => 'إضافة مستخدم', 'module' => 'users'],
            ['name' => 'edit_users', 'label' => 'تعديل مستخدم', 'module' => 'users'],
            ['name' => 'delete_users', 'label' => 'حذف مستخدم', 'module' => 'users'],

            // Students
            ['name' => 'view_students', 'label' => 'عرض الطلاب', 'module' => 'students'],
            ['name' => 'create_students', 'label' => 'إضافة طالب', 'module' => 'students'],
            ['name' => 'edit_students', 'label' => 'تعديل طالب', 'module' => 'students'],

            // Exams
            ['name' => 'view_exams', 'label' => 'عرض الامتحانات', 'module' => 'exams'],
            ['name' => 'manage_exams', 'label' => 'إدارة الامتحانات', 'module' => 'exams'],
        
            [
    'name'   => 'manage_roles',
    'label'  => 'إدارة الأدوار والصلاحيات',
    'module' => 'users',
],


        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
