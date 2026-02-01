<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeScheduleOverride extends Model
{
    protected $fillable = [
        'employee_id',
        'work_date',
        'work_shift_id',
        'reason',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(WorkShift::class, 'work_shift_id');
    }
}
