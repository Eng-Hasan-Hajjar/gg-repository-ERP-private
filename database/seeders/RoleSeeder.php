<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [

            // ── الأدوار العليا / المركزية ──
            ['name' => 'super_admin', 'label' => 'سوبر أدمن (الإدارة العليا الكاملة)'],
            ['name' => 'admin_general', 'label' => 'أدمن عام (إدارة النظام)'],
            ['name' => 'ceo', 'label' => 'المدير التنفيذي / CEO'],
            ['name' => 'manager', 'label' => 'مدير'],
            ['name' => 'staff', 'label' => 'موظف'],
            ['name' => 'consulting', 'label' => 'استشارات'],

            // ── مديري الأقسام الرئيسية ──
            ['name' => 'manager_finance', 'label' => 'مدير المالية والصناديق'],
            ['name' => 'manager_student_affairs', 'label' => 'مدير شؤون الطلاب'],
            ['name' => 'manager_exams', 'label' => 'مدير قسم الامتحانات'],
            ['name' => 'manager_hr', 'label' => 'مدير الموارد البشرية والمدربين'],
            ['name' => 'manager_crm_sales', 'label' => 'مدير الاستشارات والمبيعات (CRM)'],
            ['name' => 'manager_attendance', 'label' => 'مدير الدوام والإجازات'],
            ['name' => 'manager_logistics', 'label' => 'مدير اللوجستيات والأصول'],
            ['name' => 'manager_branches', 'label' => 'مدير الفروع والتوسع'],
            ['name' => 'manager_programs', 'label' => 'مدير البرامج والدبلومات'],
            ['name' => 'manager_media', 'label' => 'مدير الميديا والتسويق'],

            // ── موظفو / منسقو الأقسام ──
            ['name' => 'staff_finance', 'label' => 'موظف مالي / صندوق'],
            ['name' => 'staff_student_affairs', 'label' => 'موظف شؤون طلاب'],
            ['name' => 'staff_exams', 'label' => 'موظف امتحانات / تسجيل درجات'],
            ['name' => 'staff_hr', 'label' => 'مساعد موارد بشرية / مدربين'],
            ['name' => 'staff_crm', 'label' => 'مساعد مبيعات / متابعة ليدز'],
            ['name' => 'staff_attendance', 'label' => 'مساعد دوام وحضور'],
            ['name' => 'staff_logistics', 'label' => 'موظف لوجستيات / أصول'],
            ['name' => 'branch_manager', 'label' => 'مدير فرع'],
            ['name' => 'branch_staff', 'label' => 'موظف فرع'],



        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
