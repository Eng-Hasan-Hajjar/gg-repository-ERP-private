<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportFiltersRequest;
use App\Models\Branch;
use App\Services\Reports\ReportsService;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Reports\Request;
class ReportsController extends Controller
{
    public function __construct(private ReportsService $service)
    {
        $this->middleware(['auth']);
    }

    public function index(ReportFiltersRequest $request)
    {
        $filters = $request->validatedFilters();
        $data    = $this->service->getDashboard($filters);

        return view('reports.index', [
            'branches' => Branch::orderBy('name')->get(),
            'data'     => $data,
        ]);
    }

public function exportPdf(Request $request)
{
    $from = $request->get('from', now()->startOfMonth()->toDateString());
    $to   = $request->get('to', now()->endOfMonth()->toDateString());

    $branchId = $request->get('branch_id');

    $rows = $this->getReportRows($from, $to, $branchId); // نفس الدالة المستخدمة بالواجهة

    $branch = $branchId ? Branch::find($branchId) : null;

    $pdf = Pdf::loadView('attendance.reports.pdf', [
        'rows'   => $rows,
        'from'   => $from,
        'to'     => $to,
        'branch' => $branch,
    ])->setPaper('a4', 'landscape');

    return $pdf->download('attendance-report.pdf');
}

    public function exportExcel(ReportFiltersRequest $request)
    {
        $filters = $request->validatedFilters();
        $data    = $this->service->getDashboard($filters);

        // إذا Excel غير مثبت، لا نكسر
        if (!class_exists(Excel::class)) {
            return redirect()->route('reports.index', $filters)
                ->with('error', 'Excel غير مفعّل بعد. ثبّت maatwebsite/excel أولاً.');
        }

        return Excel::download(new \App\Exports\DashboardReportExport($data), 'reports_' . now()->format('Ymd_His') . '.xlsx');
    }




    public function executive(ReportFiltersRequest $request)
{
    $filters = $request->validatedFilters();
    $data = $this->service->getDashboard($filters);

    return view('reports.executive', compact('data'));
}

public function branchesMap()
{
    $branches = Branch::withCount('students')->get();
    return view('reports.branches-map', compact('branches'));
}




}
