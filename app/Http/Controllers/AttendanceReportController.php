<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Branch;
use App\Models\Employee;
use App\Exports\AttendanceReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as Pdf;
use Illuminate\Support\Facades\DB;

class AttendanceReportController extends Controller
{
    // ─── دالة مشتركة لبناء الـ query ───────────────────────────
    private function buildQuery(string $from, string $to, ?int $branchId, ?int $employeeId)
    {
        return AttendanceRecord::query()
            ->select([
                'employee_id',
                DB::raw("SUM(worked_minutes)  as worked_minutes"),
                DB::raw("SUM(break_minutes)   as break_minutes"),
                DB::raw("SUM(late_minutes)    as late_minutes"),
                DB::raw("SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absent_days"),
                DB::raw("SUM(CASE WHEN status='leave'  THEN 1 ELSE 0 END) as leave_days"),
                DB::raw("SUM(CASE WHEN status IN ('present','late') THEN 1 ELSE 0 END) as present_days"),
                DB::raw("COUNT(*) as total_days"),
            ])
            ->whereBetween('work_date', [$from, $to])
            ->when($branchId,    fn($q) => $q->whereHas('employee', fn($x) => $x->where('branch_id',  $branchId)))
            ->when($employeeId,  fn($q) => $q->where('employee_id', $employeeId))
            ->groupBy('employee_id')
            ->with('employee.branch');
    }

    // ─── تحديد الفترة بناءً على period ─────────────────────────
    private function resolveDates(Request $request): array
    {
        $period = $request->get('period', 'custom');

        return match($period) {
            'daily'   => [now()->toDateString(), now()->toDateString()],
            'weekly'  => [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
            'monthly' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
            default   => [
                $request->get('from', now()->startOfMonth()->toDateString()),
                $request->get('to',   now()->endOfMonth()->toDateString()),
            ],
        };
    }

    // ─── صفحة التقارير ──────────────────────────────────────────
    public function index(Request $request)
    {
        [$from, $to] = $this->resolveDates($request);

        $branchId   = $request->get('branch_id');
        $employeeId = $request->get('employee_id');

        $rows = $this->buildQuery($from, $to, $branchId, $employeeId)->get();

        return view('attendance.reports', [
            'rows'      => $rows,
            'from'      => $from,
            'to'        => $to,
            'branches'  => Branch::orderBy('name')->get(),
            'employees' => Employee::orderBy('full_name')->get(),
            'period'    => $request->get('period', 'custom'),
        ]);
    }

    // ─── تصدير Excel ────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        [$from, $to] = $this->resolveDates($request);

        return Excel::download(
            new AttendanceReportExport($from, $to, $request->get('branch_id'), $request->get('employee_id')),
            "attendance_{$from}_{$to}.xlsx"
        );
    }

    // ─── تصدير PDF ──────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        [$from, $to] = $this->resolveDates($request);

        $rows = $this->buildQuery(
            $from, $to,
            $request->get('branch_id'),
            $request->get('employee_id')
        )->get();

        // تفاصيل يومية لكل موظف (للتقرير المفصّل)
        $details = AttendanceRecord::query()
            ->with('employee')
            ->whereBetween('work_date', [$from, $to])
            ->when($request->get('branch_id'),   fn($q) => $q->whereHas('employee', fn($x) => $x->where('branch_id',  $request->get('branch_id'))))
            ->when($request->get('employee_id'), fn($q) => $q->where('employee_id', $request->get('employee_id')))
            ->orderBy('employee_id')
            ->orderBy('work_date')
            ->get()
            ->groupBy('employee_id');

        $pdf = Pdf::loadView('attendance.report_pdf', compact('rows', 'from', 'to', 'details'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("attendance_{$from}_{$to}.pdf");
    }
}