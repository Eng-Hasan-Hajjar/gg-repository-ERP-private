<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxTransaction;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use App\Models\PaymentPlan;
use App\Models\Lead;
use App\Models\Student;
use App\Models\StudentCrmInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FinancialTransactionController extends Controller
{
    /**
     * ✅ تسجيل دفعة جديدة (تكون في حالة draft)
     */
    public function store(Request $request)
    {
        $request->validate([
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'cashbox_id' => 'required|exists:cashboxes,id',
            'diploma_id' => 'required|exists:diplomas,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $account = FinancialAccount::findOrFail($request->financial_account_id);
        $cashbox = Cashbox::findOrFail($request->cashbox_id);

        // البحث عن خطة الدفع
        $plan = PaymentPlan::where('diploma_id', $request->diploma_id)
            ->where(function ($q) use ($account) {
                $q->where('student_id', $account->accountable_id)
                    ->orWhere('lead_id', $account->accountable_id);
            })
            ->first();

        // التحقق من العملة
        if ($plan && $plan->currency != $cashbox->currency) {
            return redirect()->back()->with('currency_error', $plan->currency);
        }

        // التحقق من المبلغ المتبقي
        if ($plan) {
            $paid = CashboxTransaction::where('financial_account_id', $request->financial_account_id)
                ->where('diploma_id', $request->diploma_id)
                ->where('type', 'in')
                ->where('status', 'posted')
                ->sum('amount');

            $remaining = $plan->total_amount - $paid;

            if ($request->amount > $remaining) {
                return back()->withErrors(['amount' => 'المبلغ أكبر من المتبقي في خطة الدفع']);
            }
        }

        // إنشاء الحركة المالية
        CashboxTransaction::create([
            'cashbox_id' => $request->cashbox_id,
            'financial_account_id' => $request->financial_account_id,
            'diploma_id' => $request->diploma_id,
            'trx_date' => now(),
            'type' => 'in',
            'amount' => $request->amount,
            'currency' => $cashbox->currency,
            'category' => 'registration',
            'notes' => $request->notes,
            'status' => 'draft',
            'posted_at' => null,
        ]);

        return back()->with('success', 'تم تسجيل الدفعة بنجاح');
    }

    /**
     * ✅ ترحيل الحركة المالية وتحويل الـ Lead إلى Student تلقائياً
     */
    public function post(CashboxTransaction $trx)
    {
        Log::info('========== بدء الترحيل ==========');

        if ($trx->status === 'posted') {
            return redirect()->back()->with('error', 'الحركة مرحّلة مسبقاً');
        }

        DB::beginTransaction();

        try {
            // 1️⃣ ترحيل الحركة
            $trx->status = 'posted';
            $trx->posted_at = now();
            $trx->save();

            // 2️⃣ جلب الحساب المالي
            $account = $trx->financialAccount;

            if ($account && $account->accountable_type === 'App\\Models\\Lead') {

                $lead = Lead::find($account->accountable_id);

                if ($lead && $lead->registration_status === 'pending') {

                    // 🔥 إنشاء الطالب
                    $student = new Student();
                    $student->university_id = $this->generateUniversityId();
                    $student->first_name = $lead->first_name ?? explode(' ', $lead->full_name)[0] ?? $lead->full_name;
                    $student->last_name = $lead->last_name ?? (explode(' ', $lead->full_name)[1] ?? '');
                    $student->full_name = $lead->full_name;
                    $student->phone = $lead->phone;
                    $student->whatsapp = $lead->whatsapp;
                    $student->branch_id = $lead->branch_id;
                    $student->mode = 'onsite';
                    $student->status = 'active';
                    $student->registration_status = 'confirmed';
                    $student->is_confirmed = true;
                    $student->confirmed_at = now();
                    $student->certificate_agreement = false;
                    $student->save();

                    // 3️⃣ نقل الدبلومات
                    foreach ($lead->diplomas as $diploma) {
                        $student->diplomas()->attach($diploma->id, [
                            'is_primary' => $diploma->pivot->is_primary ?? false,
                            'enrolled_at' => now(),
                            'status' => 'active',
                        ]);
                    }

                    // 4️⃣ نقل بيانات CRM (حتى لو كانت فارغة)
                    $crmData = [
                        'student_id' => $student->id,
                        'first_contact_date' => $lead->first_contact_date,
                        'source' => $lead->source ?? 'crm',
                        'stage' => $lead->stage ?? 'converted',
                        'organization' => $lead->organization,
                        'need' => $lead->need,
                        'notes' => $lead->notes ?? 'تم التحويل من CRM',
                        'residence' => $lead->residence,
                        'age' => $lead->age,
                        'email' => $lead->email,
                        'job' => $lead->job,
                        'country' => $lead->country,
                        'province' => $lead->province,
                        'study' => $lead->study,
                        'converted_at' => now(),
                        'created_by' => $lead->created_by,
                    ];

                    // إزالة الحقول الفارغة
                    $crmData = array_filter($crmData, fn($v) => !is_null($v) && $v !== '');

                    // إضافة بيانات أساسية إذا كانت فارغة
                    if (empty($crmData['source']))
                        $crmData['source'] = 'crm';
                    if (empty($crmData['stage']))
                        $crmData['stage'] = 'converted';

                    StudentCrmInfo::create($crmData);
                    Log::info('تم إنشاء CRM للطالب', $crmData);

                    // 5️⃣ نقل خطط الدفع
                    PaymentPlan::where('lead_id', $lead->id)->update([
                        'lead_id' => null,
                        'student_id' => $student->id,
                    ]);

                    // 6️⃣ تحديث الحساب المالي
                    $account->accountable_type = 'App\\Models\\Student';
                    $account->accountable_id = $student->id;
                    $account->save();

                    // 7️⃣ تحديث الـ Lead
                    $lead->registration_status = 'converted';
                    $lead->student_id = $student->id;
                    $lead->registered_at = now();
                    $lead->save();

                    DB::commit();

                    return redirect()
                        ->route('students.show', $student->id)
                        ->with('success', '🎉 تم ترحيل الحركة وتحويل العميل إلى طالب بنجاح!');
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'تم ترحيل الحركة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('خطأ: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * إنشاء رقم جامعي فريد
     */
    private function generateUniversityId(): string
    {
        do {
            $id = 'STU-' . now()->format('Y') . '-' . strtoupper(Str::random(6));
        } while (Student::where('university_id', $id)->exists());

        return $id;
    }
}