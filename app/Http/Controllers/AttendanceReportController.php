<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Branch;
use App\Models\Employee;
use App\Exports\AttendanceReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'from' => ['nullable','date'],
            'to'   => ['nullable','date','after_or_equal:from'],
            'branch_id' => ['nullable','exists:branches,id'],
        ]);

        $from = $data['from'] ?? now()->startOfMonth()->toDateString();
        $to   = $data['to']   ?? now()->endOfMonth()->toDateString();

        $rows = AttendanceRecord::query()
            ->select([
                'employee_id',
                DB::raw("SUM(worked_minutes) as worked_minutes"),
                DB::raw("SUM(late_minutes) as late_minutes"),
                DB::raw("SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absent_days"),
                DB::raw("SUM(CASE WHEN status='leave' THEN 1 ELSE 0 END) as leave_days"),
                DB::raw("SUM(CASE WHEN status='present' OR status='late' THEN 1 ELSE 0 END) as present_days"),
            ])
            ->whereBetween('work_date', [$from,$to])
            ->when(!empty($data['branch_id']), function($q) use ($data){
                $q->whereHas('employee', fn($x)=>$x->where('branch_id',$data['branch_id']));
            })
            ->groupBy('employee_id')
            ->with('employee.branch')
            ->get();

        return view('attendance.reports', [
            'rows'=>$rows,
            'from'=>$from,
            'to'=>$to,
            'branches'=>Branch::orderBy('name')->get(),
        ]);
    }

    
public function exportExcel(Request $request)
{
    $from = $request->get('from', now()->startOfMonth()->toDateString());
    $to   = $request->get('to', now()->endOfMonth()->toDateString());
    $branchId = $request->get('branch_id');

    return Excel::download(new AttendanceReportExport($from,$to,$branchId), "attendance_report_{$from}_{$to}.xlsx");
}

public function exportPdf(Request $request)
{
    $from = $request->get('from', now()->startOfMonth()->toDateString());
    $to   = $request->get('to', now()->endOfMonth()->toDateString());
    $branchId = $request->get('branch_id');

    $rows = AttendanceRecord::query()
        ->select([
            'employee_id',
            \DB::raw("SUM(worked_minutes) as worked_minutes"),
            \DB::raw("SUM(late_minutes) as late_minutes"),
            \DB::raw("SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absent_days"),
            \DB::raw("SUM(CASE WHEN status='leave' THEN 1 ELSE 0 END) as leave_days"),
            \DB::raw("SUM(CASE WHEN status='present' OR status='late' THEN 1 ELSE 0 END) as present_days"),
        ])
        ->whereBetween('work_date', [$from,$to])
        ->when($branchId, fn($q)=>$q->whereHas('employee', fn($x)=>$x->where('branch_id',$branchId)))
        ->groupBy('employee_id')
        ->with('employee')
        ->get();

    return Pdf::loadView('attendance.report_pdf', compact('rows','from','to'))
        ->download("attendance_report_{$from}_{$to}.pdf");
}


}
