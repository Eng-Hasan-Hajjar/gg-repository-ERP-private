<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxTransaction;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use App\Models\PaymentPlan;


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
        /*
                $plan = PaymentPlan::where('student_id', $studentId)
                    ->where('diploma_id', $request->diploma_id)
                    ->first();

                $cashbox = Cashbox::find($request->cashbox_id);

                if ($plan && $plan->currency != $cashbox->currency) {

                    return back()->withErrors([
                        'currency' => 'عملة الصندوق لا تطابق عملة خطة الدفع'
                    ]);

                }*/




        // ======================================
// جلب خطة الدفع الخاصة بالطالب
// ======================================

        $account = FinancialAccount::findOrFail($request->financial_account_id);

        $studentId = $account->accountable_id;

        $plan = PaymentPlan::where('student_id', $studentId)
            ->where('diploma_id', $request->diploma_id)
            ->first();

        // ======================================
// التحقق من العملة
// ======================================

        $cashbox = Cashbox::findOrFail($request->cashbox_id);

        if ($plan && $plan->currency != $cashbox->currency) {

            return redirect()
                ->back()
                ->with('currency_error', $plan->currency);

        }

        // ======================================
// منع الدفع أكثر من المتبقي
// ======================================

        if ($plan) {

            $paid = CashboxTransaction::where('financial_account_id', $request->financial_account_id)
                ->where('diploma_id', $request->diploma_id)
                ->where('type', 'in')
                ->where('status', 'posted')
                ->sum('amount');

            $remaining = $plan->total_amount - $paid;

            if ($request->amount > $remaining) {

                return back()->withErrors([
                    'amount' => 'المبلغ أكبر من المتبقي في خطة الدفع'
                ]);

            }

        }






        $account = FinancialAccount::findOrFail($request->financial_account_id);

        if ($account->accountable_type === \App\Models\Lead::class) {

            $lead = \App\Models\Lead::find($account->accountable_id);

            if ($lead && $lead->registration_status !== 'pending') {
                abort(403, 'تم تحويل هذا العميل إلى طالب. أضف الدفعات من صفحة الطالب.');
            }
        }




        $cashbox = Cashbox::findOrFail($request->cashbox_id);

        CashboxTransaction::create([
            'cashbox_id' => $request->cashbox_id,
            'financial_account_id' => $request->financial_account_id,
            'diploma_id' => $request->diploma_id,
            'trx_date' => now(),
            'type' => 'in',
            'amount' => $request->amount,
            'currency' => $cashbox->currency, // ✅ الصحيح
            'category' => 'registration',
            'notes' => $request->notes,
            'status' => 'draft',
            'posted_at' => now(),
        ]);

        return back()->with('success', 'تم تسجيل الدفعة بنجاح');
    }






    public function post(CashboxTransaction $trx)
    {

        if ($trx->status === 'posted') {
            return back()->with('error', 'الحركة مرحّلة مسبقاً');
        }

        // ترحيل الحركة
        $trx->update([
            'status' => 'posted',
            'posted_at' => now()
        ]);

        // =========================
        // تحويل Lead إلى Student
        // =========================

        $account = $trx->financialAccount;

        if ($account && $account->accountable_type === \App\Models\Lead::class) {

            $lead = \App\Models\Lead::find($account->accountable_id);

            if ($lead && $lead->registration_status === 'pending') {

                app(\App\Http\Controllers\LeadController::class)
                    ->convertToStudent($lead);

            }

        }

        return back()->with('success', 'تم ترحيل الحركة بنجاح');
    }





}