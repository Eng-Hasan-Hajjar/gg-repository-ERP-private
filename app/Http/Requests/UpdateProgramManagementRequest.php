<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'trainer_id' => 'nullable|exists:employees,id',

            'communication_manager' => 'nullable|string|max:255',

            'price' => 'nullable|numeric|min:0',

            'campaign_start' => 'nullable|date',
            'campaign_end' => 'nullable|date|after_or_equal:campaign_start',

            'campaign_budget' => 'nullable|numeric|min:0',

            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',

            'mid_exam' => 'nullable|date|before:final_exam',
            'final_exam' => 'nullable|date|after:mid_exam',

            'details_file' => 'nullable|file|mimes:pdf,xlsx,xls,doc,docx|max:5120',

            'confirmed_students' => 'nullable|integer|min:0',

            'graduates_count' => 'nullable|integer|min:0',

            'admin_session_1_link' => 'nullable|url|max:500',
            'admin_session_2_link' => 'nullable|url|max:500',
            'admin_session_3_link' => 'nullable|url|max:500',
            'evaluations_done_link' => 'nullable|url|max:500',
        ];
    }

    public function messages(): array
    {
        return [

            'trainer_id.exists' =>
                'المدرب المحدد غير موجود في النظام.',

            'communication_manager.string' =>
                'يجب أن يكون اسم مسؤول التواصل نصاً صحيحاً.',

            'communication_manager.max' =>
                'يجب ألا يتجاوز اسم مسؤول التواصل 255 حرفاً.',


            'price.numeric' =>
                'يجب إدخال سعر صحيح للدبلومة.',

            'price.min' =>
                'لا يمكن أن يكون سعر الدبلومة رقماً سالباً.',


            'campaign_start.date' =>
                'يرجى إدخال تاريخ صحيح لبداية الحملة.',

            'campaign_end.date' =>
                'يرجى إدخال تاريخ صحيح لنهاية الحملة.',

            'campaign_end.after_or_equal' =>
                'يجب أن يكون تاريخ نهاية الحملة بعد أو مساوياً لتاريخ بدايتها.',


            'campaign_budget.numeric' =>
                'يجب إدخال رقم صحيح لميزانية الحملة.',

            'campaign_budget.min' =>
                'لا يمكن أن تكون ميزانية الحملة رقماً سالباً.',


            'start_date.date' =>
                'يرجى إدخال تاريخ صحيح لبداية البرنامج.',

            'end_date.date' =>
                'يرجى إدخال تاريخ صحيح لنهاية البرنامج.',

            'end_date.after_or_equal' =>
                'يجب أن يكون تاريخ نهاية البرنامج بعد أو مساوياً لتاريخ بدايته.',


            'mid_exam.date' =>
                'يرجى إدخال تاريخ صحيح للامتحان النصفي.',

            'mid_exam.before' =>
                'يجب أن يكون الامتحان النصفي قبل الامتحان النهائي.',

            'final_exam.date' =>
                'يرجى إدخال تاريخ صحيح للامتحان النهائي.',

            'final_exam.after' =>
                'يجب أن يكون الامتحان النهائي بعد الامتحان النصفي.',


            'details_file.file' =>
                'يجب أن يكون ملف التفاصيل ملفاً صالحاً.',

            'details_file.mimes' =>
                'يجب أن يكون ملف التفاصيل بصيغة: PDF أو Excel أو Word.',

            'details_file.max' =>
                'يجب ألا يتجاوز حجم ملف التفاصيل 5 ميغابايت.',


            'confirmed_students.integer' =>
                'يجب إدخال عدد صحيح للطلاب المثبتين.',

            'confirmed_students.min' =>
                'لا يمكن أن يكون عدد الطلاب المثبتين أقل من صفر.',


            'graduates_count.integer' =>
                'يجب إدخال عدد صحيح للخريجين.',

            'graduates_count.min' =>
                'لا يمكن أن يكون عدد الخريجين أقل من صفر.',
        ];
    }
}