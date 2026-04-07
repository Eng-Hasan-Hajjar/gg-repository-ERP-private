<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $strict = $this->boolean('strict_mode');

        return [

            'full_name' => 'required|string|max:190',
            'phone' => 'required|string|max:50',
            'first_contact_date' => 'required|date',
            'age' => 'required|integer|min:1|max:120',
            'job' => 'required|string|max:120',
            'branch_id' => 'required|exists:branches,id',
            'country' => 'required|string|max:100',
            'source' => 'required|in:ad,referral,social,website,expo,other',
            'stage' => 'required|in:new,follow_up,interested,registered,rejected,postponed',
            'diploma_ids' => 'required|array|min:1',
            'diploma_ids.*' => 'exists:diplomas,id',
            'study' => 'required|string|max:150',

            // ===== حقول تصبح required فقط عند strict_mode =====
            'whatsapp' => $strict ? 'required|string|max:120' : 'nullable|string|max:120',
            'email' => $strict ? 'required|email|max:190' : 'nullable|required_if:stage,registered|email|max:190',
            'residence' => $strict ? 'nullable|string|max:190' : 'nullable|string|max:190',
            'organization' => $strict ? 'required|string|max:190' : 'nullable|string|max:190',
            'province' => $strict ? 'required|string|max:100' : 'nullable|string|max:100',
            'need' => $strict ? 'nullable|string' : 'nullable|string',
            'notes' => $strict ? 'required|string|max:200' : 'nullable|string|max:200',

        ];
    }

    public function messages(): array
    {
        return [

            // ===== البيانات الأساسية =====
            'full_name.required' => 'يجب إدخال الاسم الكامل',
            'full_name.string' => 'الاسم يجب أن يكون نصاً',
            'full_name.max' => 'الاسم طويل جداً',

            'phone.required' => 'يجب إدخال رقم الهاتف',
            'phone.max' => 'رقم الهاتف طويل جداً',

            'whatsapp.required' => 'يجب إدخال رقم الواتساب',

            'email.required' => 'يجب إدخال البريد الإلكتروني',
            'email.required_if' => 'يجب إدخال البريد الإلكتروني عند اختيار مرحلة مسجّل',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',

            'job.required' => 'يجب إدخال المهنة',

            // ===== معلومات التواصل =====
            'first_contact_date.required' => 'يجب تحديد تاريخ أول تواصل',
            'first_contact_date.date' => 'تاريخ غير صالح',

            'age.required' => 'يجب إدخال العمر',
            'age.integer' => 'العمر يجب أن يكون رقم',
            'age.min' => 'العمر غير صحيح',
            'age.max' => 'العمر كبير جداً',

            'residence.required' => 'يجب إدخال مكان السكن',

            'organization.required' => 'يجب إدخال جهة العمل / المؤسسة',

            // ===== الموقع الجغرافي =====
            'country.required' => 'يجب إدخال الدولة',
            'province.required' => 'يجب إدخال المحافظة',
            'study.required' => 'يجب إدخال مجال الدراسة',

            // ===== الاختيارات =====
            'branch_id.required' => 'يجب اختيار الفرع',
            'branch_id.exists' => 'الفرع المختار غير موجود',

            'source.required' => 'يجب تحديد مصدر العميل',
            'source.in' => 'قيمة المصدر غير صحيحة',

            'stage.required' => 'يجب تحديد المرحلة',
            'stage.in' => 'قيمة المرحلة غير صحيحة',

            // ===== الدبلومات =====
            'diploma_ids.required' => 'يجب اختيار دبلومة واحدة على الأقل',
            'diploma_ids.array' => 'اختيار الدبلومات غير صحيح',
            'diploma_ids.min' => 'يجب اختيار دبلومة واحدة على الأقل',
            'diploma_ids.*.exists' => 'إحدى الدبلومات المختارة غير موجودة',

            // ===== النصوص =====
            'need.required' => 'يجب كتابة احتياج العميل',
            'notes.required' => 'يجب كتابة ملاحظات',

        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'الاسم الكامل',
            'phone' => 'رقم الهاتف',
            'whatsapp' => 'رقم الواتساب',
            'email' => 'البريد الإلكتروني',
            'job' => 'المهنة',
            'first_contact_date' => 'تاريخ أول تواصل',
            'age' => 'العمر',
            'residence' => 'مكان السكن',
            'organization' => 'الجهة',
            'country' => 'الدولة',
            'province' => 'المحافظة',
            'study' => 'الدراسة',
            'branch_id' => 'الفرع',
            'source' => 'المصدر',
            'stage' => 'المرحلة',
            'diploma_ids' => 'الدبلومات',
            'need' => 'الاحتياج',
            'notes' => 'الملاحظات',
        ];
    }
}