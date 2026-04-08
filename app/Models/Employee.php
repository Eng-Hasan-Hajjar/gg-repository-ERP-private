<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Employee extends Model
{
    use Auditable;
    protected $fillable = [
        'code',
        'full_name',
        'type',
        'phone',
        'email',
        'branch_id',
        'secondary_branch_id',
        'job_title',
        'status',
        'notes',
        'user_id',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function diplomas(): BelongsToMany
    {
        return $this->belongsToMany(Diploma::class, 'diploma_employee')->withTimestamps();
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(EmployeePayout::class);
    }

    public function schedules()
    {
        return $this->hasMany(\App\Models\EmployeeSchedule::class);
    }
    public function attendanceRecords()
    {
        return $this->hasMany(\App\Models\AttendanceRecord::class);
    }
    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class, 'assigned_to');
    }

    public function scheduleOverrides()
    {
        return $this->hasMany(EmployeeScheduleOverride::class);
    }





    public function user()
    {
        return $this->belongsTo(User::class);
    }




    public function reports()
    {
        return $this->hasMany(TaskReport::class);
    }

    public function scopeTrainers($query)
    {
        return $query->where('type', 'trainer');
    }



    /*


    protected static function booted()
{
    static::addGlobalScope('branch', function ($query) {
        if (!auth()->check()) return;

        $user = auth()->user();
        if ($user->hasRole('super_admin') || $user->hasRole('manager_attendance') ) return;

        $employee = \App\Models\Employee::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->first();

        if ($employee) {
            $branchIds = collect([$employee->branch_id, $employee->secondary_branch_id])
                ->filter()->unique()->all();

            if (count($branchIds)) {
                $query->whereIn('branch_id', $branchIds);
            }
        }
    });
}
*/
protected static function booted()
{
    static::addGlobalScope('branch', function ($query) {

        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // السوبر أدمن يرى كل شيء
        if ($user->hasRole('super_admin')) {
            return;
        }

        // جلب الموظف المرتبط بالمستخدم
        $employee = Employee::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->first();

        if (!$employee) {
            return;
        }

        $branchIds = collect([
            $employee->branch_id,
            $employee->secondary_branch_id
        ])
        ->filter()
        ->unique()
        ->values()
        ->all();

        if (!empty($branchIds)) {
            $query->whereIn('branch_id', $branchIds);
        }

    });
}


    public function secondaryBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'secondary_branch_id');
    }

    /**
     * جلب IDs الفروع التي ينتمي لها الموظف (الرئيسي + الثانوي)
     */
    public function getBranchIdsAttribute(): array
    {
        return collect([$this->branch_id, $this->secondary_branch_id])
            ->filter()
            ->unique()
            ->values()
            ->all();
    }




}
