<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxTransaction;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;

class FinancialTransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'cashbox_id' => 'required|exists:cashboxes,id',
            'diploma_id' => 'required|exists:diplomas,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

       CashboxTransaction::create([
            'cashbox_id' => $request->cashbox_id,
            'financial_account_id' => $request->financial_account_id,
            'diploma_id' => $request->diploma_id,
            'trx_date' => now(),
            'type' => 'in',
            'amount' => $request->amount,
            'currency' => 'USD',
            'category' => 'registration',
            'notes' => $request->notes,
            'status' => 'posted',
            'posted_at' => now(),
        ]);

        return back()->with('success','تم تسجيل الدفعة بنجاح');
    }
}