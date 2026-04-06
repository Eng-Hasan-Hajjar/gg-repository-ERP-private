<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditable;
use App\Models\Role;
class User extends Authenticatable implements MustVerifyEmail
{
    use Auditable;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_otp_code',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_otp_expires_at' => 'datetime',
            'email_otp_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permission) {
                $q->where('name', $permission);
            })->exists();
    }




    public function isOnline(): bool
    {
        $session = $this->sessions
            ->whereNull('logout_at')
            ->sortByDesc('last_activity')
            ->first();

        if (!$session) {
            return false;
        }

        $timeout = 1200000000000000000;

        return now()->diffInSeconds($session->last_activity) <= $timeout;
    }

    public function getLastSeenAttribute(): ?string
    {
        $session = $this->sessions
            ->sortByDesc('last_activity')
            ->first();

        if (!$session) {
            return null;
        }

        if ($this->isOnline()) {
            return 'متصل الآن';
        }

        return $session->last_activity?->diffForHumans();
    }
    /*
            if (!$session || !$session->last_activity) {
                return null;
            }
    */





    public function workedSecondsOn($date): int
    {
        return $this->sessions()
            ->whereDate('work_date', $date)
            ->get()
            ->sum(function ($session) {

                if (!$session->login_at) {
                    return 0;
                }

                $end = $session->logout_at ?? now();

                if ($end->lessThan($session->login_at)) {
                    return 0;
                }

                return $session->login_at->diffInSeconds($end);
            });
    }





    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function todaySession()
    {
        return $this->hasOne(UserSession::class)
            ->where('work_date', now()->toDateString());
    }

  




    public function todaySessions()
    {
        return $this->hasMany(UserSession::class)
            ->whereDate('work_date', today());
    }


    public function getTodayWorkedSecondsAttribute(): int
    {
        $sessions = $this->todaySessions()->get(); // ← نجبرها تكون Collection

        return $sessions->sum(function ($session) {

            if (!$session->login_at) {
                return 0;
            }

            $end = $session->logout_at ?? now();

            // حماية من السالب
            if ($end->lessThan($session->login_at)) {
                return 0;
            }

            return $session->login_at->diffInSeconds($end);
        });
    }




    public function getTodayWorkedFormattedAttribute(): string
    {
        $seconds = $this->today_worked_seconds;

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        return "{$hours} ساعة {$minutes} دقيقة";
    }



    public function employee()
    {
        return $this->hasOne(Employee::class);
    }






    public function getTodayWorkedHoursAttribute()
{
    return explode(' ', $this->today_worked_formatted)[0] . ' ' .
           explode(' ', $this->today_worked_formatted)[1];
}

public function getTodayWorkedMinutesAttribute()
{
    return explode(' ', $this->today_worked_formatted)[2] . ' ' .
           explode(' ', $this->today_worked_formatted)[3];
}
























    public function getCurrentActivityAttribute()
    {
        $session = $this->sessions
            ->whereNull('logout_at')
            ->sortByDesc('last_activity')
            ->first();

        if (!$session) {
            return 'نشاط غير معروف';
        }


        $route = $session->current_route;

        if (!$route) {
            return 'داخل النظام';
        }
        $routes = [

            // Dashboard
            'dashboard' => 'لوحة التحكم',

            // Profile
            'profile.edit' => 'تعديل الملف الشخصي',
            'profile.update' => 'حفظ الملف الشخصي',

            // Students
            'students.index' => 'عرض الطلاب',
            'students.create' => 'إضافة طالب',
            'students.store' => 'حفظ طالب جديد',
            'students.edit' => 'تعديل بيانات طالب',
            'students.update' => 'تحديث بيانات طالب',
            'students.destroy' => 'حذف طالب',
            'students.confirm' => 'تأكيد تسجيل طالب',

            'students.extra.edit' => 'تعديل بيانات إضافية للطالب',
            'students.extra.update' => 'حفظ البيانات الإضافية للطالب',

            'students.profile.edit' => 'تعديل ملف الطالب',
            'students.profile.update' => 'حفظ ملف الطالب',

            // Diplomas
            'diplomas.index' => 'عرض البرامج / الدبلومات',
            'diplomas.create' => 'إضافة برنامج',
            'diplomas.edit' => 'تعديل برنامج',
            'diplomas.update' => 'تحديث برنامج',
            'diplomas.destroy' => 'حذف برنامج',
            'diplomas.toggle' => 'تفعيل / إيقاف برنامج',

            // Branches
            'branches.index' => 'عرض الفروع',
            'branches.create' => 'إضافة فرع',
            'branches.edit' => 'تعديل فرع',
            'branches.update' => 'تحديث فرع',
            'branches.destroy' => 'حذف فرع',

            // Employees
            'employees.index' => 'عرض الموظفين',
            'employees.create' => 'إضافة موظف',
            'employees.store' => 'حفظ موظف جديد',
            'employees.edit' => 'تعديل موظف',
            'employees.update' => 'تحديث بيانات موظف',
            'employees.destroy' => 'حذف موظف',
            'employees.createUser' => 'إنشاء حساب مستخدم لموظف',

            // Contracts
            'employees.contracts.index' => 'عرض عقود الموظف',
            'employees.contracts.create' => 'إضافة عقد موظف',
            'employees.contracts.store' => 'حفظ عقد موظف',
            'employees.contracts.edit' => 'تعديل عقد موظف',
            'employees.contracts.update' => 'تحديث عقد موظف',
            'employees.contracts.destroy' => 'حذف عقد موظف',

            // Payouts
            'employees.payouts.index' => 'عرض مستحقات الموظف',
            'employees.payouts.create' => 'إضافة مستحق مالي',
            'employees.payouts.store' => 'حفظ مستحق مالي',
            'employees.payouts.edit' => 'تعديل مستحق مالي',
            'employees.payouts.update' => 'تحديث مستحق مالي',
            'employees.payouts.destroy' => 'حذف مستحق مالي',
            'employees.payouts.markPaid' => 'تسجيل مستحق كمدفوع',

            // Assets
            'assets.index' => 'عرض الأصول',
            'assets.create' => 'إضافة أصل',
            'assets.edit' => 'تعديل أصل',
            'assets.update' => 'تحديث أصل',
            'assets.destroy' => 'حذف أصل',

            // Asset Categories
            'asset-categories.index' => 'عرض فئات الأصول',
            'asset-categories.create' => 'إضافة فئة أصل',
            'asset-categories.edit' => 'تعديل فئة أصل',
            'asset-categories.update' => 'تحديث فئة أصل',
            'asset-categories.destroy' => 'حذف فئة أصل',

            // Cashboxes
            'cashboxes.index' => 'عرض الصناديق المالية',
            'cashboxes.create' => 'إنشاء صندوق مالي',
            'cashboxes.edit' => 'تعديل صندوق مالي',
            'cashboxes.update' => 'تحديث صندوق مالي',
            'cashboxes.destroy' => 'حذف صندوق مالي',

            // Cashbox Transactions
            'cashboxes.transactions.index' => 'عرض حركات الصندوق',
            'cashboxes.transactions.create' => 'إضافة حركة مالية',
            'cashboxes.transactions.store' => 'حفظ حركة مالية',
            'cashboxes.transactions.edit' => 'تعديل حركة مالية',
            'cashboxes.transactions.update' => 'تحديث حركة مالية',
            'cashboxes.transactions.destroy' => 'حذف حركة مالية',
            'cashboxes.transactions.post' => 'ترحيل حركة مالية',

            // Attendance
            'attendance.index' => 'عرض الحضور',
            'attendance.today.create' => 'تسجيل حضور اليوم',
            'attendance.checkin' => 'تسجيل دخول موظف',
            'attendance.checkout' => 'تسجيل خروج موظف',
            'attendance.edit' => 'تعديل سجل حضور',
            'attendance.update' => 'تحديث سجل حضور',

            'attendance.calendar' => 'تقويم الحضور',

            'attendance.reports' => 'تقارير الحضور',

            // Tasks
            'tasks.index' => 'عرض المهام',
            'tasks.create' => 'إنشاء مهمة',
            'tasks.store' => 'حفظ مهمة',
            'tasks.edit' => 'تعديل مهمة',
            'tasks.update' => 'تحديث مهمة',
            'tasks.destroy' => 'حذف مهمة',
            'tasks.quickStatus' => 'تغيير حالة مهمة',

            // Leaves
            'leaves.index' => 'عرض طلبات الإجازة',
            'leaves.create' => 'إنشاء طلب إجازة',
            'leaves.store' => 'حفظ طلب إجازة',
            'leaves.show' => 'عرض طلب إجازة',
            'leaves.approve' => 'الموافقة على الإجازة',
            'leaves.reject' => 'رفض طلب الإجازة',

            // Exams
            'exams.index' => 'عرض الامتحانات',
            'exams.create' => 'إنشاء امتحان',
            'exams.edit' => 'تعديل امتحان',
            'exams.update' => 'تحديث امتحان',
            'exams.destroy' => 'حذف امتحان',

            'exams.results.edit' => 'إدخال درجات امتحان',
            'exams.results.update' => 'تحديث درجات امتحان',

            // CRM Leads
            'leads.index' => 'عرض العملاء المحتملين',
            'leads.create' => 'إضافة عميل محتمل',
            'leads.edit' => 'تعديل عميل محتمل',
            'leads.update' => 'تحديث عميل محتمل',
            'leads.destroy' => 'حذف عميل محتمل',
            'leads.convert' => 'تحويل عميل إلى طالب',

            'leads.followups.store' => 'إضافة متابعة عميل',
            'leads.followups.destroy' => 'حذف متابعة عميل',

            // CRM Reports
            'crm.reports.index' => 'تقارير العملاء المحتملين',
            'crm.reports.pdf' => 'تصدير تقرير العملاء PDF',

            // General Reports
            'reports.index' => 'عرض التقارير',
            'reports.pdf' => 'تصدير تقرير PDF',
            'reports.excel' => 'تصدير تقرير Excel',

            'reports.executive' => 'التقرير التنفيذي',
            'reports.branches.map' => 'خريطة الفروع',
            'reports.students.growth' => 'نمو الطلاب',
            'reports.revenue.branches' => 'إيرادات الفروع',
            'reports.system.alerts' => 'تنبيهات النظام',
            'reports.charts' => 'رسوم بيانية',

            // Task Reports
            'reports.task.index' => 'تقارير المهام',
            'reports.task.create' => 'إنشاء تقرير مهمة',
            'reports.task.store' => 'حفظ تقرير مهمة',
            'reports.task.show' => 'عرض تقرير مهمة',
            'reports.task.destroy' => 'حذف تقرير مهمة',

            // Finance
            'finance.dashboard' => 'لوحة التحكم المالية',
            'finance.reports.diplomas' => 'تقرير البرامج المالية',
            'finance.reports.profit' => 'تقرير الأرباح',
            'finance.reports.daily' => 'التقرير المالي اليومي',

            // Program Management
            'programs.management.index' => 'إدارة البرامج',
            'programs.management.edit' => 'تعديل برنامج',
            'programs.management.update' => 'تحديث برنامج',
            'programs.management.show' => 'عرض تفاصيل برنامج',

            // Media Requests
            'media.index' => 'طلبات الميديا',
            'media.show' => 'عرض طلب ميديا',
            'media.update' => 'تحديث طلب ميديا',

            // Admin
            'admin.users.index' => 'إدارة المستخدمين',
            'admin.users.create' => 'إضافة مستخدم',
            'admin.users.edit' => 'تعديل مستخدم',

            'admin.roles.index' => 'إدارة الأدوار',
            'admin.roles.create' => 'إضافة دور',
            'admin.roles.edit' => 'تعديل دور',

            'admin.permissions.index' => 'إدارة الصلاحيات',

            'admin.audit.index' => 'مركز التدقيق',

        ];

    
return $routes[$route] ?? 'داخل النظام';

    }















}
