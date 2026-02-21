<?php

// app/Http/Requests/LeadStoreRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadStoreRequest extends FormRequest
{
  public function authorize(): bool { return true; }

 public function rules(): array
{
    return [

        'full_name' => 'required|string|max:190',
        'phone' => 'required|string|max:50',
        'whatsapp' => 'required|string|max:120',

        'email' => 'required|email|max:190',
        'job' => 'required|string|max:120',

        'first_contact_date' => 'required|date',
        'residence' => 'required|string|max:190',
        'age' => 'required|integer|min:1|max:120',

        'organization' => 'required|string|max:190',

        'source' => 'required|in:ad,referral,social,website,expo,other',
        'stage' => 'required|in:new,follow_up,interested,registered,rejected,postponed',

        'branch_id' => 'required|exists:branches,id',

        'country'  => 'required|string|max:100',
        'province' => 'required|string|max:100',
        'study'    => 'required|string|max:150',

        'need' => 'required|string',
        'notes' => 'required|string',

        'diploma_ids' => 'required|array|min:1',
        'diploma_ids.*' => 'exists:diplomas,id',
    ];
}



public function messages(): array
{
    return [

        // ===== البيانات الأساسية =====
        'full_name.required' => 'يجب إدخال الاسم الكامل',
        'full_name.string'   => 'الاسم يجب أن يكون نصاً',
        'full_name.max'      => 'الاسم طويل جداً',

        'phone.required' => 'يجب إدخال رقم الهاتف',
        'phone.max'      => 'رقم الهاتف طويل جداً',

        'whatsapp.required' => 'يجب إدخال رقم الواتساب',

        'email.required' => 'يجب إدخال البريد الإلكتروني',
        'email.email'    => 'صيغة البريد الإلكتروني غير صحيحة',

        'job.required' => 'يجب إدخال المهنة',

        // ===== معلومات التواصل =====
        'first_contact_date.required' => 'يجب تحديد تاريخ أول تواصل',
        'first_contact_date.date'     => 'تاريخ غير صالح',

        'age.required' => 'يجب إدخال العمر',
        'age.integer'  => 'العمر يجب أن يكون رقم',
        'age.min'      => 'العمر غير صحيح',
        'age.max'      => 'العمر كبير جداً',

        'residence.required' => 'يجب إدخال مكان السكن',

        'organization.required' => 'يجب إدخال جهة العمل / المؤسسة',

        // ===== الموقع الجغرافي =====
        'country.required'  => 'يجب إدخال الدولة',
        'province.required' => 'يجب إدخال المحافظة',
        'study.required'    => 'يجب إدخال مجال الدراسة',

        // ===== الاختيارات =====
        'branch_id.required' => 'يجب اختيار الفرع',
        'branch_id.exists'   => 'الفرع المختار غير موجود',

        'source.required' => 'يجب تحديد مصدر العميل',
        'source.in'       => 'قيمة المصدر غير صحيحة',

        'stage.required' => 'يجب تحديد المرحلة',
        'stage.in'       => 'قيمة المرحلة غير صحيحة',

        // ===== الدبلومات =====
        'diploma_ids.required' => 'يجب اختيار دبلومة واحدة على الأقل',
        'diploma_ids.array'    => 'اختيار الدبلومات غير صحيح',
        'diploma_ids.min'      => 'يجب اختيار دبلومة واحدة على الأقل',
        'diploma_ids.*.exists' => 'إحدى الدبلومات المختارة غير موجودة',

        // ===== النصوص =====
        'need.required'  => 'يجب كتابة احتياج العميل',
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
