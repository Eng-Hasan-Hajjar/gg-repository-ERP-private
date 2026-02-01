<?php

namespace App\Exports;

use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendanceReportExport implements FromCollection
{
    public function __construct(public string $from, public string $to, public ?int $branchId = null) {}

    public function collection()
    {
        return AttendanceRecord::query()
            ->select([
                'employee_id',
                DB::raw("SUM(worked_minutes) as worked_minutes"),
                DB::raw("SUM(late_minutes) as late_minutes"),
                DB::raw("SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absent_days"),
                DB::raw("SUM(CASE WHEN status='leave' THEN 1 ELSE 0 END) as leave_days"),
                DB::raw("SUM(CASE WHEN status='present' OR status='late' THEN 1 ELSE 0 END) as present_days"),
            ])
            ->whereBetween('work_date', [$this->from,$this->to])
            ->when($this->branchId, function($q){
                $q->whereHas('employee', fn($x)=>$x->where('branch_id',$this->branchId));
            })
            ->groupBy('employee_id')
            ->get();
    }
}
