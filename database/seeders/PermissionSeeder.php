<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | اللوحة الرئيسية والتقارير
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_dashboard','label'=>'عرض لوحة التحكم','module'=>'dashboard'],
            ['name'=>'view_reports','label'=>'عرض التقارير','module'=>'dashboard'],
            ['name'=>'export_reports','label'=>'تصدير التقارير','module'=>'dashboard'],
            ['name'=>'view_executive_dashboard','label'=>'عرض اللوحة التنفيذية','module'=>'dashboard'],


            /*
            |--------------------------------------------------------------------------
            | CRM الاستشارات والمبيعات
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_leads','label'=>'عرض العملاء المحتملين','module'=>'crm'],
            ['name'=>'create_leads','label'=>'إضافة عميل محتمل','module'=>'crm'],
            ['name'=>'edit_leads','label'=>'تعديل عميل محتمل','module'=>'crm'],
            ['name'=>'delete_leads','label'=>'حذف عميل محتمل','module'=>'crm'],
            ['name'=>'assign_leads','label'=>'تعيين عميل لموظف','module'=>'crm'],
            ['name'=>'convert_leads','label'=>'تحويل إلى طالب','module'=>'crm'],
            ['name'=>'view_crm_reports','label'=>'عرض تقارير المبيعات','module'=>'crm'],
            ['name'=>'export_crm_reports','label'=>'تصدير تقارير المبيعات','module'=>'crm'],


            /*
            |--------------------------------------------------------------------------
            | الطلاب
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_students','label'=>'عرض الطلاب','module'=>'students'],
            ['name'=>'create_students','label'=>'إضافة طالب','module'=>'students'],
            ['name'=>'edit_students','label'=>'تعديل طالب','module'=>'students'],
            ['name'=>'delete_students','label'=>'حذف طالب','module'=>'students'],
            ['name'=>'register_students','label'=>'تسجيل الطالب بالدبلومة','module'=>'students'],
            ['name'=>'archive_students','label'=>'أرشفة طالب','module'=>'students'],
            ['name'=>'view_student_financials','label'=>'عرض الوضع المالي للطالب','module'=>'students'],


            /*
            |--------------------------------------------------------------------------
            | الامتحانات
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_exams','label'=>'عرض الامتحانات','module'=>'exams'],
            ['name'=>'create_exams','label'=>'إضافة امتحان','module'=>'exams'],
            ['name'=>'edit_exams','label'=>'تعديل امتحان','module'=>'exams'],
            ['name'=>'delete_exams','label'=>'حذف امتحان','module'=>'exams'],
            ['name'=>'enter_grades','label'=>'إدخال العلامات','module'=>'exams'],
            ['name'=>'publish_results','label'=>'اعتماد النتائج','module'=>'exams'],
            ['name'=>'export_results','label'=>'تصدير النتائج','module'=>'exams'],


            /*
            |--------------------------------------------------------------------------
            | المالية والصناديق
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_cashboxes','label'=>'عرض الصناديق','module'=>'finance'],
            ['name'=>'create_cashboxes','label'=>'إنشاء صندوق','module'=>'finance'],
            ['name'=>'edit_cashboxes','label'=>'تعديل صندوق','module'=>'finance'],
            ['name'=>'delete_cashboxes','label'=>'حذف صندوق','module'=>'finance'],
            ['name'=>'add_transaction','label'=>'إضافة حركة مالية','module'=>'finance'],
            ['name'=>'approve_transaction','label'=>'اعتماد حركة مالية','module'=>'finance'],
            ['name'=>'view_financial_reports','label'=>'عرض التقارير المالية','module'=>'finance'],
            ['name'=>'export_financial_reports','label'=>'تصدير التقارير المالية','module'=>'finance'],


            /*
            |--------------------------------------------------------------------------
            | الدوام والإجازات
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_attendance','label'=>'عرض الدوام','module'=>'attendance'],
            ['name'=>'mark_attendance','label'=>'تسجيل حضور وانصراف','module'=>'attendance'],
            ['name'=>'edit_attendance','label'=>'تعديل الدوام','module'=>'attendance'],
            ['name'=>'view_leaves','label'=>'عرض الإجازات','module'=>'attendance'],
            ['name'=>'create_leaves','label'=>'طلب إجازة','module'=>'attendance'],
            ['name'=>'approve_leaves','label'=>'اعتماد الإجازة','module'=>'attendance'],
            ['name'=>'reject_leaves','label'=>'رفض الإجازة','module'=>'attendance'],
            ['name'=>'export_attendance_reports','label'=>'تصدير تقارير الدوام','module'=>'attendance'],


            /*
            |--------------------------------------------------------------------------
            | المهام
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_tasks','label'=>'عرض المهام','module'=>'tasks'],
            ['name'=>'create_tasks','label'=>'إضافة مهمة','module'=>'tasks'],
            ['name'=>'edit_tasks','label'=>'تعديل مهمة','module'=>'tasks'],
            ['name'=>'delete_tasks','label'=>'حذف مهمة','module'=>'tasks'],
            ['name'=>'assign_tasks','label'=>'تعيين مهمة','module'=>'tasks'],
            ['name'=>'complete_tasks','label'=>'إنهاء مهمة','module'=>'tasks'],
            ['name'=>'archive_tasks','label'=>'أرشفة مهمة','module'=>'tasks'],


            /*
            |--------------------------------------------------------------------------
            | الموارد البشرية
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_employees','label'=>'عرض الموظفين','module'=>'hr'],
            ['name'=>'create_employees','label'=>'إضافة موظف','module'=>'hr'],
            ['name'=>'edit_employees','label'=>'تعديل موظف','module'=>'hr'],
            ['name'=>'delete_employees','label'=>'حذف موظف','module'=>'hr'],
            ['name'=>'manage_contracts','label'=>'إدارة العقود','module'=>'hr'],
            ['name'=>'manage_salaries','label'=>'إدارة المستحقات','module'=>'hr'],
            ['name'=>'assign_trainers','label'=>'ربط المدربين بالدبلومات','module'=>'hr'],
            ['name'=>'view_hr_reports','label'=>'عرض تقارير الموارد البشرية','module'=>'hr'],


            /*
            |--------------------------------------------------------------------------
            | المستخدمون والصلاحيات
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_users','label'=>'عرض المستخدمين','module'=>'users'],
            ['name'=>'create_users','label'=>'إضافة مستخدم','module'=>'users'],
            ['name'=>'edit_users','label'=>'تعديل مستخدم','module'=>'users'],
            ['name'=>'delete_users','label'=>'حذف مستخدم','module'=>'users'],
            ['name'=>'manage_roles','label'=>'إدارة الأدوار','module'=>'users'],
            ['name'=>'assign_permissions','label'=>'تعيين الصلاحيات','module'=>'users'],
            ['name'=>'view_audit_logs','label'=>'عرض سجل التعديلات','module'=>'users'],


            /*
            |--------------------------------------------------------------------------
            | الأصول واللوجستيات
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_assets','label'=>'عرض الأصول','module'=>'assets'],
            ['name'=>'create_assets','label'=>'إضافة أصل','module'=>'assets'],
            ['name'=>'edit_assets','label'=>'تعديل أصل','module'=>'assets'],
            ['name'=>'delete_assets','label'=>'حذف أصل','module'=>'assets'],
            ['name'=>'assign_assets','label'=>'تسليم أصل','module'=>'assets'],
            ['name'=>'track_maintenance','label'=>'متابعة الصيانة','module'=>'assets'],


            /*
            |--------------------------------------------------------------------------
            | الفروع
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_branches','label'=>'عرض الفروع','module'=>'branches'],
            ['name'=>'create_branches','label'=>'إضافة فرع','module'=>'branches'],
            ['name'=>'edit_branches','label'=>'تعديل فرع','module'=>'branches'],
            ['name'=>'delete_branches','label'=>'حذف فرع','module'=>'branches'],
            ['name'=>'assign_branch_data','label'=>'ربط البيانات بالفرع','module'=>'branches'],


            /*
            |--------------------------------------------------------------------------
            | الدبلومات
            |--------------------------------------------------------------------------
            */
            ['name'=>'view_diplomas','label'=>'عرض الدبلومات','module'=>'diplomas'],
            ['name'=>'create_diplomas','label'=>'إضافة دبلومة','module'=>'diplomas'],
            ['name'=>'edit_diplomas','label'=>'تعديل دبلومة','module'=>'diplomas'],
            ['name'=>'delete_diplomas','label'=>'حذف دبلومة','module'=>'diplomas'],
            ['name'=>'assign_students_to_diploma','label'=>'تسجيل طلاب بالدبلومة','module'=>'diplomas'],
            ['name'=>'assign_trainers_to_diploma','label'=>'ربط مدربين بالدبلومة','module'=>'diplomas'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name'=>$permission['name']],
                $permission
            );
        }
    }
}
