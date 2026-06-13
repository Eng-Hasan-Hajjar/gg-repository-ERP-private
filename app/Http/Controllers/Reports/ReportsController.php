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
        $data = $this->service->getDashboard($filters);

        return view('reports.index', [
            'branches' => Branch::orderBy('name')->get(),
            'data' => $data,
        ]);
    }



    public function exportPdf(ReportFiltersRequest $request)
    {
        $filters = $request->validatedFilters();
        $data = $this->service->getDashboard($filters);

        $pdf = Pdf::loadView('reports.pdf', [
            'data' => $data,
            'filters' => $filters,
        ]);

        return $pdf->setPaper('a4', 'landscape')
            ->download('report.pdf');
    }
















    public function exportExcel(ReportFiltersRequest $request)
    {
        $filters = $request->validatedFilters();
        $data = $this->service->getDashboard($filters);

        // إذا Excel غير مثبت، لا نكسر
        if (!class_exists(Excel::class)) {
            return redirect()->route('reports.index', $filters)
                ->with('error', 'Excel غير مفعّل بعد. ثبّت maatwebsite/excel أولاً.');
        }

        return Excel::download(new \App\Exports\DashboardReportExport($data), 'reports_' . now()->format('Ymd_His') . '.xlsx');
    }




    public function executive(ReportFiltersRequest $request)
    {
        // ✅ الهيكلية القديمة محفوظة
        $filters = $request->validatedFilters();
        $data = $this->service->getDashboard($filters);

        // ✅ إحصائيات إضافية للوحة التنفيذية
        $studentTotal = \App\Models\Student::count();
        $studentConfirmed = \App\Models\Student::where('is_confirmed', true)->count();
        $studentPending = \App\Models\Student::where('registration_status', 'pending')->count();
        $studentToday = \App\Models\Student::whereDate('created_at', today())->count();

        $studentGrowth = \App\Models\Student::selectRaw(
            "DATE_FORMAT(created_at,'%Y-%m') as m, COUNT(*) as t"
        )->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('m')->orderBy('m')->get();

        $revenueToday = \App\Models\CashboxTransaction::whereDate(
            DB::raw('COALESCE(trx_date,created_at)'),
            today()
        )->where('type', 'in')->where('status', 'posted')->sum('amount');

        $revenueMonth = \App\Models\CashboxTransaction::whereMonth(
            DB::raw('COALESCE(trx_date,created_at)'),
            now()->month
        )->whereYear(DB::raw('COALESCE(trx_date,created_at)'), now()->year)
            ->where('type', 'in')->where('status', 'posted')->sum('amount');

        $revenueDaily = \App\Models\CashboxTransaction::selectRaw(
            "DAY(COALESCE(trx_date,created_at)) as d, SUM(amount) as t"
        )->whereMonth(DB::raw('COALESCE(trx_date,created_at)'), now()->month)
            ->whereYear(DB::raw('COALESCE(trx_date,created_at)'), now()->year)
            ->where('type', 'in')->where('status', 'posted')
            ->groupBy('d')->orderBy('d')->get();

        $revenueBranch = DB::table('cashbox_transactions')
            ->join('cashboxes', 'cashbox_transactions.cashbox_id', '=', 'cashboxes.id')
            ->join('branches', 'cashboxes.branch_id', '=', 'branches.id')
            ->where('cashbox_transactions.type', 'in')
            ->where('cashbox_transactions.status', 'posted')
            ->whereMonth(DB::raw('COALESCE(trx_date,cashbox_transactions.created_at)'), now()->month)
            ->selectRaw('branches.name as branch, SUM(cashbox_transactions.amount) as amount')
            ->groupBy('branches.name')
            ->get();

        $hrStats = [
            'trainers' => \App\Models\Employee::where('type', 'trainer')->count(),
            'employees' => \App\Models\Employee::where('type', 'employee')->count(),
            'active_trainers' => \App\Models\Employee::where('type', 'trainer')->where('status', 'active')->count(),
            'active_employees' => \App\Models\Employee::where('type', 'employee')->where('status', 'active')->count(),
        ];

        $presentToday = \App\Models\AttendanceRecord::whereDate('work_date', today())
            ->whereIn('status', ['present', 'late'])->count();
        $absentToday = \App\Models\AttendanceRecord::whereDate('work_date', today())
            ->where('status', 'absent')->count();
        $lateToday = \App\Models\AttendanceRecord::whereDate('work_date', today())
            ->where('status', 'late')->count();

        $pendingLeaves = \App\Models\LeaveRequest::where('status', 'pending')->count();
        $approvedLeaves = \App\Models\LeaveRequest::where('status', 'approved')
            ->where('start_date', '>=', today())->count();

        $taskStats = [
            'total' => \App\Models\Task::count(),
            'done' => \App\Models\Task::where('status', 'done')->count(),
            'todo' => \App\Models\Task::whereIn('status', ['todo', 'in_progress'])->count(),
            'overdue' => \App\Models\Task::where('status', '!=', 'done')
                ->whereDate('due_date', '<', today())->count(),
        ];

        $assetStats = [
            'total' => \App\Models\Asset::count(),
            'good' => \App\Models\Asset::where('condition', 'good')->count(),
            'maintenance' => \App\Models\Asset::where('condition', 'maintenance')->count(),
            'retired' => \App\Models\Asset::where('condition', 'retired')->count(),
        ];

        $assetRequests = [
            'pending' => \App\Models\AssetRequest::where('status', 'pending')->count(),
            'approved' => \App\Models\AssetRequest::where('status', 'approved')->count(),
            'rejected' => \App\Models\AssetRequest::where('status', 'rejected')->count(),
        ];

        // ✅ صح
        $diplomaStats = [
            'total' => \App\Models\Diploma::count(),
            'active' => \App\Models\Diploma::where('is_active', true)->count(),
            'online' => \App\Models\Diploma::where('type', 'online')->count(),
        ];

        $studentsByBranch = \App\Models\Branch::withCount('students')
            ->orderByDesc('students_count')->get();

        $leadsTotal = \App\Models\Lead::count();
        $leadsPending = \App\Models\Lead::where('registration_status', 'pending')->count();
        $leadsConverted = \App\Models\Lead::where('registration_status', 'registered')->count();

        $cutoff48 = now()->subHours(48)->toDateTimeString();

        $urgentLeads = (int) DB::selectOne("
    SELECT COUNT(*) as cnt FROM (
        SELECT leads.id
        FROM leads
        LEFT JOIN lead_followups ON lead_followups.lead_id = leads.id
        WHERE leads.registration_status = 'pending'
        AND leads.stage NOT IN ('rejected','registered','postponed')
        GROUP BY leads.id
        HAVING MAX(lead_followups.created_at) <= '{$cutoff48}'
            OR MAX(lead_followups.created_at) IS NULL
    ) as t
")->cnt;

        return view('reports.executive', compact(
            'data',  // ✅ محفوظ كما كان
            'studentTotal',
            'studentConfirmed',
            'studentPending',
            'studentToday',
            'studentGrowth',
            'revenueToday',
            'revenueMonth',
            'revenueDaily',
            'revenueBranch',
            'hrStats',
            'presentToday',
            'absentToday',
            'lateToday',
            'pendingLeaves',
            'approvedLeaves',
            'taskStats',
            'assetStats',
            'assetRequests',
            'diplomaStats',
            'studentsByBranch',
            'leadsTotal',
            'leadsPending',
            'leadsConverted',
            'urgentLeads'
        ));
    }

    public function branchesMap()
    {
        $branches = Branch::withCount('students')->get();
        return view('reports.branches-map', compact('branches'));
    }


    public function charts(ReportFiltersRequest $request)
    {
        $filters = $request->validatedFilters();
        $data = $this->service->getDashboard($filters);





        // ================== FINANCE CHARTS ==================

        $range = request('range', 'month');

        switch ($range) {
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
                $to = request('to');
                break;
            default:
                $from = Carbon::now()->startOfMonth();
                $to = Carbon::now()->endOfMonth();
        }

        // دخل مقابل مصروف يومي
        $financeDaily = CashboxTransaction::whereBetween('trx_date', [$from, $to])
            ->where('cashbox_transactions.status', 'posted')
            ->select(
                DB::raw('DATE(trx_date) as date'),
                DB::raw('SUM(CASE WHEN type="in" THEN amount ELSE 0 END) as total_in'),
                DB::raw('SUM(CASE WHEN type="out" THEN amount ELSE 0 END) as total_out')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // أرباح حسب الدبلومة
        $financeDiplomas = CashboxTransaction::whereBetween('trx_date', [$from, $to])
            ->where('status', 'posted')
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
        $topPrograms = CashboxTransaction::whereBetween('trx_date', [$from, $to])
            ->where('cashbox_transactions.status', 'posted')
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
        $topBranches = CashboxTransaction::whereBetween('trx_date', [$from, $to])
            ->where('cashbox_transactions.status', 'posted')
            ->where('cashbox_transactions.type', 'in')
            ->join('cashboxes', 'cashbox_transactions.cashbox_id', '=', 'cashboxes.id')
            ->join('branches', 'cashboxes.branch_id', '=', 'branches.id')
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
