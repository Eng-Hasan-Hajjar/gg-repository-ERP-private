<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as Pdf;


class LeadReportController extends Controller
{
  public function index(Request $request)
  {
    $from = $request->input('from');
    $to   = $request->input('to');

    $q = Lead::query();

    if ($from) $q->whereDate('created_at','>=',$from);
    if ($to)   $q->whereDate('created_at','<=',$to);

    $byBranch = (clone $q)
      ->selectRaw('branch_id, count(*) as total,
        sum(case when registration_status="converted" then 1 else 0 end) as converted')
      ->groupBy('branch_id')
      ->with('branch')
      ->get();

    $summary = [
      'total' => (clone $q)->count(),
      'converted' => (clone $q)->where('registration_status','converted')->count(),
      'pending' => (clone $q)->where('registration_status','pending')->count(),
      'lost' => (clone $q)->where('registration_status','lost')->count(),
    ];

    return view('crm.reports.index', compact('byBranch','summary','from','to'));
  }




  public function exportPdf(Request $request)
{
    $from = $request->input('from');
    $to   = $request->input('to');

    $q = Lead::query();

    if ($from) $q->whereDate('created_at','>=',$from);
    if ($to)   $q->whereDate('created_at','<=',$to);

    $byBranch = (clone $q)
        ->selectRaw('branch_id, count(*) as total,
            sum(case when registration_status="converted" then 1 else 0 end) as converted')
        ->groupBy('branch_id')
        ->with('branch')
        ->get();

    $summary = [
        'total' => (clone $q)->count(),
        'converted' => (clone $q)->where('registration_status','converted')->count(),
        'pending' => (clone $q)->where('registration_status','pending')->count(),
        'lost' => (clone $q)->where('registration_status','lost')->count(),
    ];

    $pdf = Pdf::loadView('crm.reports.pdf', compact('byBranch','summary','from','to'))
        ->setPaper('a4','landscape');

    return $pdf->download('crm-report.pdf');
}




}
