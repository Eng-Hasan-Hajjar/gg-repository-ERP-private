<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Traits\Auditable;

class AttendanceRecord extends Model
{
    use Auditable;

    protected $fillable = [
        'employee_id',
        'work_date',
        'work_shift_id',
        'check_in_at',
        'check_out_at',
        'break_start_at',
        'break_end_at',
        'break_minutes',
        'late_minutes',
        'worked_minutes',
        'status',
        'notes',
    ];

    protected $casts = [
        'work_date'      => 'date',
        'check_in_at'    => 'datetime',
        'check_out_at'   => 'datetime',
        'break_start_at' => 'datetime',
        'break_end_at'   => 'datetime',
    ];

    // ───── Relations ─────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(WorkShift::class, 'work_shift_id');
    }

    // ───── حالة الاستراحة ─────

    /**
     * هل الموظف حالياً في استراحة؟
     */
    public function getIsOnBreakAttribute(): bool
    {
        return $this->break_start_at && !$this->break_end_at;
    }

    /**
     * هل يمكن بدء استراحة؟
     * الشروط: سجّل دخول + لم يسجل خروج + ليس في استراحة حالياً
     */
  public function getCanStartBreakAttribute(): bool
{
    return $this->check_in_at
        && !$this->check_out_at
        && !$this->is_on_break
        && !$this->break_start_at; 
}

    /**
     * هل يمكن إنهاء الاستراحة؟
     */
    public function getCanEndBreakAttribute(): bool
    {
        return $this->is_on_break;
    }

    /**
     * مدة الاستراحة بالتنسيق
     */
    public function getBreakFormattedAttribute(): string
    {
        if ($this->break_minutes <= 0) {
            return '—';
        }
        $h = floor($this->break_minutes / 60);
        $m = $this->break_minutes % 60;

        if ($h > 0) {
            return "{$h} س {$m} د";
        }
        return "{$m} د";
    }

    /**
     * صافي ساعات العمل (بعد خصم الاستراحة)
     */
    public function getNetWorkedMinutesAttribute(): int
    {
        return max(0, $this->worked_minutes - $this->break_minutes);
    }

    public function getNetWorkedFormattedAttribute(): string
    {
        $total = $this->net_worked_minutes;
        $h = floor($total / 60);
        $m = $total % 60;
        return "{$h} س {$m} د";
    }

    // ───── Scopes ─────

    public function scopeReport(Builder $query, $from, $to, $branchId = null)
    {
        return $query
            ->select([
                'employee_id',
                DB::raw("SUM(worked_minutes) as worked_minutes"),
                DB::raw("SUM(break_minutes) as break_minutes"),
                DB::raw("SUM(late_minutes) as late_minutes"),
                DB::raw("SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absent_days"),
                DB::raw("SUM(CASE WHEN status='leave' THEN 1 ELSE 0 END) as leave_days"),
                DB::raw("SUM(CASE WHEN status IN ('present','late') THEN 1 ELSE 0 END) as present_days"),
            ])
            ->whereBetween('work_date', [$from, $to])
            ->when($branchId, function ($q) use ($branchId) {
                $q->whereHas('employee', fn($x) => $x->where('branch_id', $branchId));
            })
            ->groupBy('employee_id')
            ->with('employee.branch');
    }

    // ───── Global Scope ─────


    protected static function booted()
    {
        static::addGlobalScope('branch', function ($query) {
            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();

            if ($user->hasRole('super_admin')) {
                return;
            }

            $employee = Employee::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->first();

            if ($employee && $employee->branch_id) {
                $query->whereHas('employee', function ($q) use ($employee) {
                    $q->where('branch_id', $employee->branch_id);
                });
            }
        });
    }

    // ───── Accessors ─────

    public function getStatusLabelAttribute()
    {
        return [
            'scheduled' => 'مجدول',
            'present'   => 'حاضر',
            'late'      => 'متأخر',
            'absent'    => 'غائب',
            'off'       => 'عطلة',
            'leave'     => 'إجازة',
        ][$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        return [
            'present'   => 'success',
            'late'      => 'danger',
            'absent'    => 'dark',
            'off'       => 'secondary',
            'leave'     => 'info',
            'scheduled' => 'warning',
        ][$this->status] ?? 'secondary';
    }
}