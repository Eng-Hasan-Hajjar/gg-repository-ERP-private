<?php

namespace App\Http\Controllers;

use App\Models\CashboxTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinanceController extends Controller
{

    /*
    |--------------------------------------------------
    | 1️⃣ تقرير الدبلومات (فصل عملات حقيقي)
    |--------------------------------------------------
    */
    public function diplomaReport()
    {
        $transactions = CashboxTransaction::with('diploma')
            ->where('status','posted')
            ->where('type','in')
            ->whereNotNull('diploma_id')
            ->get();

        $report = $transactions
            ->groupBy('diploma_id')
            ->map(function($items){

                return [
                    'diploma' => $items->first()->diploma,
                    'currencies' => $items
                        ->groupBy('currency')
                        ->map(function($rows){
                            return [
                                'total_amount' => $rows->sum('amount'),
                                'total_payments' => $rows->count(),
                            ];
                        })
                ];
            });

        return view('finance.reports.diplomas', compact('report'));
    }

    /*
    |--------------------------------------------------
    | 2️⃣ الأرباح حسب البرنامج
    |--------------------------------------------------
    */
    public function profitByProgram()
    {
        $transactions = CashboxTransaction::with('diploma')
            ->where('status','posted')
            ->where('type','in')
            ->whereNotNull('diploma_id')
            ->get();

        $report = $transactions
            ->groupBy('diploma_id')
            ->map(function($items){

                return [
                    'diploma' => $items->first()->diploma,
                    'currencies' => $items
                        ->groupBy('currency')
                        ->map(function($rows){
                            return $rows->sum('amount');
                        })
                ];
            });

        return view('finance.reports.profit', compact('report'));
    }

    /*
    |--------------------------------------------------
    | 3️⃣ التقرير اليومي
    |--------------------------------------------------
    */
    public function dailyReport(Request $request)
    {
        $date = $request->date ?? Carbon::today()->toDateString();

        $transactions = CashboxTransaction::with(['cashbox','account.accountable','diploma'])
            ->whereDate('trx_date', $date)
            ->where('status','posted')
            ->get();

        $totals = $transactions
            ->groupBy('currency')
            ->map(function($items){
                return [
                    'in'  => $items->where('type','in')->sum('amount'),
                    'out' => $items->where('type','out')->sum('amount'),
                ];
            });

        return view('finance.reports.daily', compact(
            'transactions','date','totals'
        ));
    }

    /*
    |--------------------------------------------------
    | 4️⃣ لوحة التحكم المالية
    |--------------------------------------------------
    */
    public function dashboard()
    {
        $today = Carbon::today();

        $all = CashboxTransaction::where('status','posted')->get();

        $todayTotals = $all
            ->where('trx_date', $today->toDateString())
            ->groupBy('currency')
            ->map(fn($x)=>[
                'in'=>$x->where('type','in')->sum('amount'),
                'out'=>$x->where('type','out')->sum('amount'),
            ]);

        $monthTotals = $all
            ->filter(fn($x)=> 
                \Carbon\Carbon::parse($x->trx_date)->month == $today->month &&
                \Carbon\Carbon::parse($x->trx_date)->year == $today->year
            )
            ->groupBy('currency')
            ->map(fn($x)=>[
                'in'=>$x->where('type','in')->sum('amount'),
                'out'=>$x->where('type','out')->sum('amount'),
            ]);

        $totalTotals = $all
            ->groupBy('currency')
            ->map(fn($x)=>[
                'in'=>$x->where('type','in')->sum('amount'),
                'out'=>$x->where('type','out')->sum('amount'),
            ]);

        return view('finance.dashboard', compact(
            'todayTotals',
            'monthTotals',
            'totalTotals'
        ));
    }
}