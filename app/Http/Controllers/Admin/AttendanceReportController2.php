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
        $end   = Carbon::parse($month)->endOfMonth();

        $users = User::with('roles')->get();

        return view('admin.reports.monthly', compact('users','start','end','month'));
    }

    public function exportPdf(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $start = Carbon::parse($month)->startOfMonth();
        $end   = Carbon::parse($month)->endOfMonth();

        $users = User::with('roles')->get();

        $pdf = Pdf::loadView('admin.reports.monthly_pdf', [
            'users' => $users,
            'start' => $start,
            'end'   => $end,
            'month' => $month,
        ]);

        return $pdf->setPaper('a4', 'landscape')
                   ->download('attendance_'.$month.'.pdf');
    }
}