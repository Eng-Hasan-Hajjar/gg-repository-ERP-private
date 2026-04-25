<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as Pdf;
use Carbon\Carbon;
use App\Models\User;

class AttendanceReportController2 extends Controller
{
    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $users = User::with('roles')->get();

        return view('admin.reports.monthly', compact('users', 'start', 'end', 'month'));
    }

    public function exportPdf(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $users = User::with('roles')->get();

        $pdf = Pdf::loadView('admin.reports.monthly_pdf', [
            'users' => $users,
            'start' => $start,
            'end' => $end,
            'month' => $month,
        ]);

        return $pdf->setPaper('a4', 'landscape')
            ->download('attendance_' . $month . '.pdf');
    }




    public function exportExcel(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $start = \Carbon\Carbon::parse($month)->startOfMonth();
        $end = \Carbon\Carbon::parse($month)->endOfMonth();
        // $users = \App\Models\User::with('attendanceRecords')->get();
        $users = \App\Models\User::all();


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('تقرير الحضور');
        $ws->setRightToLeft(true);

        $ws->setCellValue('A1', 'المستخدم');
        $ws->setCellValue('B1', 'إجمالي الساعات');
        $ws->setCellValue('C1', 'الدقائق');
        $ws->getStyle('A1:C1')->getFont()->setBold(true);

        $row = 2;
        foreach ($users as $user) {
            $totalSeconds = 0;
            for ($date = $start->copy(); $date <= $end; $date->addDay()) {
                $totalSeconds += $user->workedSecondsOn($date);
            }
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);

            $ws->setCellValue("A{$row}", $user->name);
            $ws->setCellValue("B{$row}", $hours);
            $ws->setCellValue("C{$row}", $minutes);
            $row++;
        }

        foreach (['A', 'B', 'C'] as $col) {
            $ws->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'attendance-' . $month . '.xlsx';




        while (ob_get_level()) {
            ob_end_clean();
        }




        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }



}