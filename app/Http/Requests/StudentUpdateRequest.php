<?php
// app/Http/Requests/StudentUpdateRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // ── بيانات أساسية ──
            'first_name'  => 'required|string|max:120',
            'last_name'   => 'nullable|string|max:120',
            'full_name'   => 'required|string|max:190',
            'phone'       => 'nullable|string|max:50',
            'whatsapp'    => 'nullable|string|max:120',
            'branch_id'   => 'required|exists:branches,id',
            'mode'        => 'required|in:onsite,online',
            'status'      => 'required|string',

            'certificate_agreement' => 'sometimes|boolean',

            // ── الدبلومات ──
            'diploma_ids'   => 'nullable|array',
            'diploma_ids.*' => 'exists:diplomas,id',

            // ── CRM — كل شيء nullable ──
            'crm'              => 'nullable|array',
            'crm.source'       => 'nullable|in:ad,referral,social,website,expo,other',
            'crm.stage'        => 'nullable|in:new,follow_up,interested,registered,rejected,postponed',
            'crm.organization' => 'nullable|string|max:190',
            'crm.need'         => 'nullable|string',
            'crm.notes'        => 'nullable|string',
            'crm.country'      => 'nullable|string|max:100',
            'crm.province'     => 'nullable|string|max:100',
            'crm.study'        => 'nullable|string|max:150',
            'crm.job'          => 'nullable|string|max:150',
            'crm.first_contact_date' => 'nullable|date',

            // ── Profile — كل شيء nullable ──
            'profile'                        => 'nullable|array',
            'profile.arabic_full_name'       => 'nullable|string|max:190',
            'profile.nationality'            => 'nullable|string|max:120',
            'profile.birth_date'             => 'nullable|date',
            'profile.national_id'            => 'nullable|string|max:120',
            'profile.exam_score'             => 'nullable|numeric|min:0|max:999.99',
            'profile.notes'                  => 'nullable|string',
            'profile.level'                  => 'nullable|string|max:100',
            'profile.stage_in_state'         => 'nullable|string|max:120',
            'profile.education_level'        => 'nullable|string|max:120',
            'profile.message_to_send'        => 'nullable|string',

            // ── ملفات — nullable عند التعديل ──
            'profile.photo'                  => 'nullable|image|max:2048',
            'profile.info_file'              => 'nullable|file|max:5120',
            'profile.identity_file'          => 'nullable|file|max:5120',
            'profile.attendance_certificate' => 'nullable|file|max:5120',
            'profile.certificate_pdf'        => 'nullable|mimes:pdf|max:5120',
            'profile.certificate_card'       => 'nullable|file|max:5120',

            // ── ملفات الدبلومات ──
            'diplomas'                                    => 'nullable|array',
            'diplomas.*.status'                           => 'nullable|in:active,waiting,finished',
            'diplomas.*.ended_at'                         => 'nullable|date',
            'diplomas.*.notes'                            => 'nullable|string',
            'diplomas.*.attendance_certificate'           => 'nullable|file|max:5120',
            'diplomas.*.certificate_pdf'                  => 'nullable|mimes:pdf|max:5120',
            'diplomas.*.certificate_card'                 => 'nullable|file|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'يجب إدخال الاسم',
            'full_name.required'  => 'يجب إدخال الاسم الكامل',
            'branch_id.required'  => 'يجب اختيار الفرع',
            'branch_id.exists'    => 'الفرع المحدد غير موجود',
            'mode.required'       => 'يجب اختيار نوع الطالب',
            'mode.in'             => 'قيمة نوع الطالب غير صحيحة',
            'status.required'     => 'يجب تحديد حالة الطالب',

            'crm.source.in'       => 'قيمة المصدر غير صحيحة',
            'crm.stage.in'        => 'قيمة المرحلة غير صحيحة',

            'profile.exam_score.numeric' => 'العلامة يجب أن تكون رقماً',
            'profile.exam_score.min'     => 'العلامة غير صحيحة',
            'profile.photo.image'        => 'الملف يجب أن يكون صورة',
            'profile.photo.max'          => 'حجم الصورة كبير جداً',
            'profile.certificate_pdf.mimes' => 'يجب أن يكون الملف بصيغة PDF',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'الاسم',
            'full_name'  => 'الاسم الكامل',
            'branch_id'  => 'الفرع',
            'mode'       => 'نوع الطالب',
            'status'     => 'حالة الطالب',
            'crm.source' => 'مصدر العميل',
            'crm.stage'  => 'المرحلة',
            'profile.birth_date'  => 'تاريخ التولد',
            'profile.exam_score'  => 'العلامة الامتحانية',
        ];
    }
}