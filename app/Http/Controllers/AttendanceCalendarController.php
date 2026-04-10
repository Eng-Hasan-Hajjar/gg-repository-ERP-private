<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Branch;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

// سنضيف PDF حسب المكتبة المختارة

class AttendanceCalendarController extends Controller
{
    public function month(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $monthObj = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        $start = $monthObj->copy()->startOfMonth();
        $end = $monthObj->copy()->endOfMonth();

        // خرائط CSS + الحروف (بدل ما تكون في Blade)
        $clsMap = [
            'present' => 'bg-success-subtle',
            'late' => 'bg-warning-subtle',
            'absent' => 'bg-danger-subtle',
            'off' => 'bg-light',
            'leave' => 'bg-info-subtle',
            'scheduled' => 'bg-secondary-subtle',
        ];

        $letterMap = [
            'present' => 'P',
            'late' => 'L',
            'absent' => 'A',
            'off' => 'O',
            'leave' => 'V',
            'scheduled' => 'S',
        ];

        // فلترة الموظفين
        $employees = Employee::query()
            ->with('branch')
            ->where('status', 'active')
            ->when($request->filled('branch_id'), fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->filled('employee_id'), fn($q) => $q->where('id', $request->employee_id))
            ->orderBy('full_name')
            ->get();

        // جلب سجلات الدوام ضمن الشهر وتجهيزها كمصفوفة سهلة الوصول
        $records = AttendanceRecord::query()
            ->whereBetween('work_date', [$start, $end])
            ->get();



        // days: مصفوفة تواريخ الشهر
        $days = [];
        for ($d = 1; $d <= $monthObj->daysInMonth; $d++) {
            $days[] = $monthObj->copy()->day($d)->toDateString();
        }

        // recordsMap: [employee_id][date] => status
        $recordsMap = [];
        foreach ($records as $r) {
            $dateStr = $r->work_date instanceof Carbon
                ? $r->work_date->toDateString()
                : Carbon::parse($r->work_date)->toDateString();

            $recordsMap[$r->employee_id][$dateStr] = $r->status; // نخزن status فقط
        }




        // حساب ملخص كل موظف للموبايل
        $employeeSummary = [];
        foreach ($employees as $emp) {
            $present = 0;
            $absent = 0;
            $late = 0;
            $leave = 0;
            $off = 0;
            foreach ($days as $date) {
                $s = $recordsMap[$emp->id][$date] ?? null;
                if ($s === 'present')
                    $present++;
                elseif ($s === 'absent')
                    $absent++;
                elseif ($s === 'late')
                    $late++;
                elseif ($s === 'leave')
                    $leave++;
                elseif ($s === 'off')
                    $off++;
            }
            $employeeSummary[$emp->id] = [
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'leave' => $leave,
                'off' => $off,
            ];
        }




        return view('attendance.calendar', [
            'month' => $month,
            'monthObj' => $monthObj,
            'days' => $days,
            'employees' => $employees,
            'recordsMap' => $recordsMap,
            'branches' => Branch::orderBy('name')->get(),
            'clsMap' => $clsMap,
            'letterMap' => $letterMap,
            'filters' => $request->only(['branch_id', 'employee_id']),
            'employeeSummary' => $employeeSummary,
                // ← هذا كان مفقوداً
    'statusCssMap'    => [
        'present'   => 'st-present',
        'late'      => 'st-late',
        'absent'    => 'st-absent',
        'leave'     => 'st-leave',
        'off'       => 'st-off',
        'scheduled' => 'st-sched',
    ],
        ]);
    }



    private function buildCalendarData(Request $request): array
    {
        $month = $request->get('month', now()->format('Y-m'));
        $monthObj = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        $start = $monthObj->copy()->startOfMonth();
        $end = $monthObj->copy()->endOfMonth();

        $clsMap = [
            'present' => 'bg-success-subtle',
            'late' => 'bg-warning-subtle',
            'absent' => 'bg-danger-subtle',
            'off' => 'bg-light',
            'leave' => 'bg-info-subtle',
            'scheduled' => 'bg-secondary-subtle',
        ];

        $statusCssMap = [
            'present' => 'st-present',
            'late' => 'st-late',
            'absent' => 'st-absent',
            'leave' => 'st-leave',
            'off' => 'st-off',
            'scheduled' => 'st-sched',
        ];



        $letterMap = [
            'present' => 'P',
            'late' => 'L',
            'absent' => 'A',
            'off' => 'O',
            'leave' => 'V',
            'scheduled' => 'S',
        ];

        $employees = Employee::query()
            ->with('branch')
            ->where('status', 'active')
            ->when($request->filled('branch_id'), fn($q) => $q->where('branch_id', $request->branch_id))
            ->orderBy('full_name')
            ->get();

        $records = AttendanceRecord::query()
            ->whereBetween('work_date', [$start, $end])
            ->get();

        $days = [];
        for ($d = 1; $d <= $monthObj->daysInMonth; $d++) {
            $days[] = $monthObj->copy()->day($d)->toDateString();
        }

        $recordsMap = [];
        foreach ($records as $r) {
            $dateStr = $r->work_date instanceof Carbon
                ? $r->work_date->toDateString()
                : Carbon::parse($r->work_date)->toDateString();

            $recordsMap[$r->employee_id][$dateStr] = $r->status;
        }

        return [
            'month' => $month,
            'monthObj' => $monthObj,
            'days' => $days,
            'employees' => $employees,
            'recordsMap' => $recordsMap,
            'branches' => Branch::orderBy('name')->get(),
            'clsMap' => $clsMap,
            'letterMap' => $letterMap,
            'filters' => $request->only(['branch_id']),
            'statusCssMap' => $statusCssMap,

        ];
    }
    /*
        public function month(Request $request)
        {
            return view('attendance.calendar', $this->buildCalendarData($request));
        }
    */
    public function exportExcel(Request $request)
    {
        $data = $this->buildCalendarData($request);

        return Excel::download(
            new \App\Exports\AttendanceCalendarExport(
                $data['month'],
                $data['days'],
                $data['employees'],
                $data['recordsMap'],
                $data['letterMap']
            ),
            'attendance_calendar_' . $data['month'] . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $data = $this->buildCalendarData($request);

        return PDF::loadView('attendance.calendar_pdf', $data)
            ->download('attendance_calendar_' . $data['month'] . '.pdf');
    }




}
