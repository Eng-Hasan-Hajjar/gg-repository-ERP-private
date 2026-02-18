<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;


class EmployeeSchedule extends Model
{
    use Auditable;
    protected $fillable = ['employee_id','weekday','work_shift_id'];

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function shift(): BelongsTo { return $this->belongsTo(WorkShift::class,'work_shift_id'); }


    }
