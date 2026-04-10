<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'ip',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ──

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'created' => 'إنشاء',
            'updated' => 'تعديل',
            'deleted' => 'حذف',
            'login'   => 'تسجيل دخول',
            'logout'  => 'تسجيل خروج',
            default   => $this->action,
        };
    }

    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            'login'   => 'info',
            'logout'  => 'secondary',
            default   => 'secondary',
        };
    }

    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'created' => 'bi-plus-circle-fill',
            'updated' => 'bi-pencil-fill',
            'deleted' => 'bi-trash-fill',
            'login'   => 'bi-box-arrow-in-right',
            'logout'  => 'bi-box-arrow-right',
            default   => 'bi-activity',
        };
    }

    public function getModelLabelAttribute(): string
    {
        return match($this->model) {
            'User'              => 'مستخدم',
            'Role'              => 'دور',
            'Permission'        => 'صلاحية',
            'Student'           => 'طالب',
            'Lead'              => 'عميل محتمل',
            'LeadFollowup'      => 'متابعة عميل',
            'Employee'          => 'موظف',
            'EmployeeContract'  => 'عقد موظف',
            'EmployeePayout'    => 'مستحق موظف',
            'Diploma'           => 'دبلومة',
            'Branch'            => 'فرع',
            'Cashbox'           => 'صندوق مالي',
            'CashboxTransaction'=> 'حركة مالية',
            'Task'              => 'مهمة',
            'TaskReport'        => 'تقرير مهمة',
            'AttendanceRecord'  => 'سجل دوام',
            'LeaveRequest'      => 'طلب إجازة',
            'Asset'             => 'أصل',
            'Exam'              => 'امتحان',
            'MediaRequest'      => 'طلب ميديا',
            default             => $this->model ?? '—',
        };
    }

    public function getBrowserAttribute(): string
    {
        $ua = $this->user_agent ?? '';
        if (str_contains($ua, 'Chrome'))  return 'Chrome';
        if (str_contains($ua, 'Firefox')) return 'Firefox';
        if (str_contains($ua, 'Safari'))  return 'Safari';
        if (str_contains($ua, 'Edge'))    return 'Edge';
        return 'غير معروف';
    }

    public function getDeviceTypeAttribute(): string
    {
        $ua = $this->user_agent ?? '';
        if (str_contains($ua, 'Mobile') || str_contains($ua, 'Android')) return 'موبايل';
        if (str_contains($ua, 'Tablet') || str_contains($ua, 'iPad'))    return 'تابلت';
        return 'ديسكتوب';
    }

    public function getDeviceIconAttribute(): string
    {
        return match($this->device_type) {
            'موبايل' => 'bi-phone',
            'تابلت'  => 'bi-tablet',
            default  => 'bi-display',
        };
    }
}