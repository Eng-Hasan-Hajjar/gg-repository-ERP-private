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
        'employee_id','work_date','work_shift_id',
        'check_in_at','check_out_at','late_minutes','worked_minutes',
        'status','notes'
    ];

    protected $casts = [
        'work_date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function shift(): BelongsTo { return $this->belongsTo(WorkShift::class,'work_shift_id'); }


    /**
 * Scope: تقرير الدوام المجمع
 */
public function scopeReport(Builder $query, $from, $to, $branchId = null)
{
    return $query
        ->select([
            'employee_id',
            DB::raw("SUM(worked_minutes) as worked_minutes"),
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


}
