<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportFiltersRequest;
use App\Models\Branch;
use App\Models\AttendanceRecord;
use App\Models\CashboxTransaction;
use Carbon\Carbon;
use DB;

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





// ================== FINANCE CHARTS ==================

$range = request('range','month');

switch($range){
    case 'today':
        $from = Carbon::today();
        $to = Carbon::today();
        break;
    case 'week':
        $from = Carbon::now()->startOfWeek();
        $to = Carbon::now()->endOfWeek();
        break;
    case 'year':
        $from = Carbon::now()->startOfYear();
        $to = Carbon::now()->endOfYear();
        break;
    case 'custom':
        $from = request('from');
        $to   = request('to');
        break;
    default:
        $from = Carbon::now()->startOfMonth();
        $to = Carbon::now()->endOfMonth();
}

// دخل مقابل مصروف يومي
$financeDaily = CashboxTransaction::whereBetween('trx_date', [$from,$to])
    ->where('cashbox_transactions.status','posted')
    ->select(
        DB::raw('DATE(trx_date) as date'),
        DB::raw('SUM(CASE WHEN type="in" THEN amount ELSE 0 END) as total_in'),
        DB::raw('SUM(CASE WHEN type="out" THEN amount ELSE 0 END) as total_out')
    )
    ->groupBy('date')
    ->orderBy('date')
    ->get();

// أرباح حسب الدبلومة
$financeDiplomas = CashboxTransaction::whereBetween('trx_date', [$from,$to])
    ->where('status','posted')
    ->whereNotNull('diploma_id')
    ->select(
        'diploma_id',
        DB::raw('SUM(amount) as total')
    )
    ->groupBy('diploma_id')
    ->with('diploma')
    ->get();

$data['charts']['finance_daily'] = $financeDaily;
$data['charts']['finance_diplomas'] = $financeDiplomas;
$data['filters']['from'] = $from;
$data['filters']['to'] = $to;





// ================= TOP 5 PROGRAMS =================
$topPrograms = CashboxTransaction::whereBetween('trx_date', [$from,$to])
    ->where('cashbox_transactions.status','posted')
    ->whereNotNull('diploma_id')
    ->select(
        'diploma_id',
        DB::raw('SUM(amount) as total')
    )
    ->groupBy('diploma_id')
    ->orderByDesc('total')
    ->limit(5)
    ->with('diploma')
    ->get();


// ================= TOP 5 BRANCHES =================
$topBranches = CashboxTransaction::whereBetween('trx_date', [$from,$to])
    ->where('cashbox_transactions.status','posted')
    ->where('cashbox_transactions.type','in')
    ->join('cashboxes','cashbox_transactions.cashbox_id','=','cashboxes.id')
    ->join('branches','cashboxes.branch_id','=','branches.id')
    ->select(
        'branches.name as branch_name',
        DB::raw('SUM(cashbox_transactions.amount) as total')
    )
    ->groupBy('branches.name')
    ->orderByDesc('total')
    ->limit(5)
    ->get();

$data['charts']['top_programs'] = $topPrograms;
$data['charts']['top_branches'] = $topBranches;









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
