<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\PaymentInstallment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentPlanController extends Controller
{

    public function store(Request $request)
    {

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'diploma_id' => 'required|exists:diplomas,id',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required',
            'payment_type' => 'required|in:full,installments',
            'installments_count' => 'nullable|integer|min:1|max:4'
        ]);

        // منع أكثر من خطة لنفس الدبلومة
        $exists = PaymentPlan::where('student_id', $request->student_id)
            ->where('diploma_id', $request->diploma_id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'plan' => 'لا يمكن إنشاء أكثر من خطة لهذه الدبلومة'
            ]);
        }


        $paidBefore = \App\Models\CashboxTransaction::where(
            'financial_account_id',
            $request->student->financialAccount->id ?? null
        )
            ->where('diploma_id', $request->diploma_id)
            ->where('type', 'in')
            ->where('status', 'posted')
            ->sum('amount');

        if ($request->total_amount < $paidBefore) {
            return back()->withErrors([
                'plan' => 'المبلغ الإجمالي للخطة أقل من المبلغ المدفوع سابقاً'
            ]);
        }


        DB::transaction(function () use ($request) {

            $plan = PaymentPlan::create([
                'student_id' => $request->student_id,
                'diploma_id' => $request->diploma_id,
                'total_amount' => $request->total_amount,
                'payment_type' => $request->payment_type,
                'installments_count' => $request->installments_count,
                'currency' => $request->currency
            ]);

            if ($request->payment_type === 'installments' && $request->installments) {

                foreach ($request->installments as $row) {

                    PaymentInstallment::create([
                        'plan_id' => $plan->id,
                        'amount' => $row['amount'],
                        'due_date' => $row['due_date']
                    ]);

                }

            }

        });
        return redirect()
            ->route('students.show', $request->student_id)
            ->with('success', 'تم إنشاء خطة الدفع');
        //  return back()->with('success', 'تم إنشاء خطة الدفع');
    }




    public function edit(PaymentPlan $plan)
    {

        $paid = $plan->student
            ->financialAccount
            ->transactions()
            ->where('diploma_id', $plan->diploma_id)
            ->where('type', 'in')
            ->where('status', 'posted')
            ->sum('amount');

        $paymentsCount = $plan->student
            ->financialAccount
            ->transactions()
            ->where('diploma_id', $plan->diploma_id)
            ->where('type', 'in')
            ->where('status', 'posted')
            ->count();

        if ($paymentsCount > 1) {
            return back()->withErrors([
                'plan' => 'لا يمكن تعديل الخطة بعد وجود أكثر من دفعة'
            ]);
        }

        $student = $plan->student;

        return view('payments.edit_plan', compact('plan', 'student'));

    }


    public function update(Request $request, PaymentPlan $plan)
    {

        $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required',
            'payment_type' => 'required|in:full,installments',
            'installments_count' => 'nullable|integer|min:1|max:4',
            'installments.*.amount' => 'nullable|numeric|min:0',
            'installments.*.due_date' => 'nullable|date'

        ]);

        DB::transaction(function () use ($request, $plan) {

            $plan->update([
                'total_amount' => $request->total_amount,
                'currency' => $request->currency,
                'payment_type' => $request->payment_type,
                'installments_count' => $request->installments_count
            ]);

            if ($request->payment_type == 'installments' && $request->installments) {

                $plan->installments()->delete();



                foreach ($request->installments as $row) {

                    if (!empty($row['amount']) && !empty($row['due_date'])) {

                        PaymentInstallment::create([
                            'plan_id' => $plan->id,
                            'amount' => $row['amount'],
                            'due_date' => $row['due_date']
                        ]);

                    }

                }
          

            }

        });

        return redirect()
            ->route('students.show', $plan->student_id)
            ->with('success', 'تم تعديل الخطة');

    }

}