<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\PaymentInstallment;
use App\Models\Lead;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentPlanController extends Controller
{

    // ══════════════════════════════════════════════════════
    // store — يدعم lead_id أو student_id
    // ══════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'diploma_id'         => 'required|exists:diplomas,id',
            'total_amount'       => 'required|numeric|min:0',
            'currency'           => 'required',
            'payment_type'       => 'required|in:full,installments',
            'installments_count' => 'nullable|integer|min:1|max:4',
        ]);

        // ── تحديد من هو صاحب الخطة ──
        $isLead    = $request->filled('lead_id');
        $isStudent = $request->filled('student_id');

        if (!$isLead && !$isStudent) {
            return back()->withErrors(['owner' => 'يجب تحديد طالب أو عميل']);
        }

        // ── منع أكثر من خطة لنفس الدبلومة ──
        $exists = PaymentPlan::when($isLead,    fn($q) => $q->where('lead_id',    $request->lead_id))
                             ->when($isStudent, fn($q) => $q->where('student_id', $request->student_id))
                             ->where('diploma_id', $request->diploma_id)
                             ->exists();

        if ($exists) {
            return back()->withErrors(['plan' => 'لا يمكن إنشاء أكثر من خطة لهذه الدبلومة']);
        }

        // ── التحقق من أن مجموع الدفعات لا يتجاوز الإجمالي ──
        if ($request->payment_type === 'installments' && $request->installments) {
            $sumInstallments = collect($request->installments)
                ->sum(fn($row) => (float)($row['amount'] ?? 0));

            if ($sumInstallments > (float)$request->total_amount) {
                return back()
                    ->withErrors(['installments' => 'مجموع الدفعات (' . number_format($sumInstallments, 2) . ') يتجاوز المبلغ الإجمالي (' . number_format($request->total_amount, 2) . ')'])
                    ->withInput();
            }

            foreach ($request->installments as $i => $row) {
                if ((float)($row['amount'] ?? 0) <= 0) {
                    return back()
                        ->withErrors(['installments' => 'قيمة الدفعة ' . $i . ' يجب أن تكون أكبر من صفر'])
                        ->withInput();
                }
            }
        }

        DB::transaction(function () use ($request, $isLead, $isStudent) {

            $plan = PaymentPlan::create([
                'lead_id'            => $isLead    ? $request->lead_id    : null,
                'student_id'         => $isStudent ? $request->student_id : null,
                'diploma_id'         => $request->diploma_id,
                'total_amount'       => $request->total_amount,
                'payment_type'       => $request->payment_type,
                'installments_count' => $request->installments_count,
                'currency'           => $request->currency,
            ]);

            if ($request->payment_type === 'installments' && $request->installments) {
                foreach ($request->installments as $row) {
                    if (!empty($row['amount']) && !empty($row['due_date'])) {
                        PaymentInstallment::create([
                            'plan_id'  => $plan->id,
                            'amount'   => $row['amount'],
                            'due_date' => $row['due_date'],
                        ]);
                    }
                }
            }
        });

        // ── إعادة التوجيه إلى الصفحة الصحيحة ──
        if ($isLead) {
            return redirect()
                ->route('leads.show', $request->lead_id)
                ->with('success', 'تم إنشاء خطة الدفع');
        }

        return redirect()
            ->route('students.show', $request->student_id)
            ->with('success', 'تم إنشاء خطة الدفع');
    }


    // ══════════════════════════════════════════════════════
    // edit — صفحة تعديل الخطة (Lead أو Student)
    // ══════════════════════════════════════════════════════
    public function edit(PaymentPlan $plan)
    {
        // تحديد صاحب الخطة
        if ($plan->lead_id) {
            $owner          = Lead::findOrFail($plan->lead_id);
            $financialAccount = $owner->financialAccount;
        } else {
            $owner          = Student::findOrFail($plan->student_id);
            $financialAccount = $owner->financialAccount;
        }

        if (!$financialAccount) {
            $student = $plan->student_id ? $owner : null;
            $lead    = $plan->lead_id   ? $owner : null;
            return view('payments.edit_plan', compact('plan', 'student', 'lead', 'owner'));
        }

        $paymentsCount = $financialAccount
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

        $student = $plan->student_id ? $owner : null;
        $lead    = $plan->lead_id    ? $owner : null;

        return view('payments.edit_plan', compact('plan', 'student', 'lead', 'owner'));
    }


    // ══════════════════════════════════════════════════════
    // update — تحديث الخطة
    // ══════════════════════════════════════════════════════
    public function update(Request $request, PaymentPlan $plan)
    {
        $request->validate([
            'total_amount'               => 'required|numeric|min:0',
            'currency'                   => 'required',
            'payment_type'               => 'required|in:full,installments',
            'installments_count'         => 'nullable|integer|min:1|max:4',
            'installments.*.amount'      => 'nullable|numeric|min:0',
            'installments.*.due_date'    => 'nullable|date',
        ]);

        // التحقق من مجموع الدفعات
        if ($request->payment_type === 'installments' && $request->installments) {
            $sumInstallments = collect($request->installments)
                ->sum(fn($row) => (float)($row['amount'] ?? 0));

            if ($sumInstallments > (float)$request->total_amount) {
                return back()
                    ->withErrors(['installments' => 'مجموع الدفعات (' . number_format($sumInstallments, 2) . ') يتجاوز المبلغ الإجمالي (' . number_format($request->total_amount, 2) . ')'])
                    ->withInput();
            }

            foreach ($request->installments as $i => $row) {
                if (!empty($row['amount']) && (float)($row['amount']) <= 0) {
                    return back()
                        ->withErrors(['installments' => 'قيمة الدفعة ' . $i . ' يجب أن تكون أكبر من صفر'])
                        ->withInput();
                }
            }
        }

        DB::transaction(function () use ($request, $plan) {

            $plan->update([
                'total_amount'       => $request->total_amount,
                'currency'           => $request->currency,
                'payment_type'       => $request->payment_type,
                'installments_count' => $request->installments_count,
            ]);

            if ($request->payment_type === 'installments' && $request->installments) {
                $plan->installments()->delete();
                foreach ($request->installments as $row) {
                    if (!empty($row['amount']) && !empty($row['due_date'])) {
                        PaymentInstallment::create([
                            'plan_id'  => $plan->id,
                            'amount'   => $row['amount'],
                            'due_date' => $row['due_date'],
                        ]);
                    }
                }
            }
        });

        // إعادة التوجيه
        if ($plan->lead_id) {
            return redirect()
                ->route('leads.show', $plan->lead_id)
                ->with('success', 'تم تعديل الخطة');
        }

        return redirect()
            ->route('students.show', $plan->student_id)
            ->with('success', 'تم تعديل الخطة');
    }
}