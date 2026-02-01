<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'code','full_name','type','phone','email',
        'branch_id','job_title','status','notes',
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

    public function schedules(){ return $this->hasMany(\App\Models\EmployeeSchedule::class); }
    public function attendanceRecords(){ return $this->hasMany(\App\Models\AttendanceRecord::class); }
    public function tasks(){ return $this->hasMany(\App\Models\Task::class,'assigned_to'); }

public function scheduleOverrides()
{
    return $this->hasMany(EmployeeScheduleOverride::class);
}



}
