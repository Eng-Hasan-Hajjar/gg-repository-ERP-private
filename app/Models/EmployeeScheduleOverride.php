<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class EmployeeScheduleOverride extends Model
{
    use Auditable;
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
