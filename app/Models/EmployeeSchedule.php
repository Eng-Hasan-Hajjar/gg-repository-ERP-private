<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;


class EmployeeSchedule extends Model
{
    use Auditable;
    protected $fillable = ['employee_id','weekday',
    'start_time',  // ← جديد
        'end_time',    // ← جديد
        'is_off',      // ← جديد]
    ];
    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    //public function shift(): BelongsTo { return $this->belongsTo(WorkShift::class,'work_shift_id'); }


    }
