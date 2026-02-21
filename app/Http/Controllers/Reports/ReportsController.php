<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportFiltersRequest;
use App\Models\Branch;
use App\Models\AttendanceRecord;

use App\Services\Reports\ReportsService;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
//use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf as Pdf;
use Illuminate\Http\Request;
//use App\Http\Controllers\Reports\Request;
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


    
public function exportPdf(ReportFiltersRequest $request)
{
    $filters = $request->validatedFilters();
    $data    = $this->service->getDashboard($filters);

    $pdf = Pdf::loadView('reports.pdf', [
        'data'    => $data,
        'filters' => $filters,
    ]);

    return $pdf->setPaper('a4', 'landscape')
               ->download('report.pdf');
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


public function charts(ReportFiltersRequest $request)
{
    $filters = $request->validatedFilters();
    $data    = $this->service->getDashboard($filters);

    return view('reports.charts', compact('data'));
}




public function studentsGrowth(ReportsService $service)
{
    $data = $service->studentsGrowthReport();

    return view('reports.students-growth', $data);
}





public function revenuePerBranch(ReportFiltersRequest $request)
{
    $filters = $request->validatedFilters();

    $data = $this->service->revenuePerBranchReport($filters);

    return view('reports.revenue-by-branch', $data);
}




public function alerts()
{
    $data = $this->service->systemAlerts();

    return view('reports.system-alerts', $data);
}


}
