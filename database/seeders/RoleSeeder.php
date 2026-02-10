<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'super_admin', 'label' => 'سوبر أدمين'],
            ['name' => 'admin', 'label' => 'أدمين'],
            ['name' => 'manager', 'label' => 'مدير'],
            ['name' => 'staff', 'label' => 'موظف'],
            ['name' => 'student_affairs', 'label' => 'شؤون طلاب'],
            ['name' => 'exams', 'label' => 'موظف امتحانات'],
            ['name' => 'logistics', 'label' => 'لوجستيات'],
            ['name' => 'consulting', 'label' => 'استشارات'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
