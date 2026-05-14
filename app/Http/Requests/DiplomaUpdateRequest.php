<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiplomaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $diplomaId = $this->route('diploma')->id;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('diplomas', 'code')->ignore($diplomaId)],
            'field'       => ['nullable', 'string', 'max:255'],
            'is_active'   => ['nullable', 'boolean'],
            'type'        => ['required', 'in:online,onsite'],
            'details_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:102400'],
            'branch_id'   => ['required', 'exists:branches,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function messages(): array
    {
        return [
            // name
            'name.required' => 'اسم الدبلومة مطلوب ولا يمكن تركه فارغاً.',
            'name.string'   => 'اسم الدبلومة يجب أن يكون نصاً.',
            'name.max'      => 'اسم الدبلومة طويل جداً، الحد الأقصى 255 حرفاً.',

            // code
            'code.required'   => 'رمز الدبلومة مطلوب.',
            'code.string'     => 'رمز الدبلومة يجب أن يكون نصاً.',
            'code.max'        => 'رمز الدبلومة طويل جداً، الحد الأقصى 50 حرفاً.',
            'code.alpha_dash' => 'رمز الدبلومة يجب أن يحتوي على أحرف وأرقام وشرطات فقط (بدون مسافات أو رموز خاصة).',
            'code.unique'     => 'رمز الدبلومة هذا مستخدم مسبقاً لدبلومة أخرى، يرجى اختيار رمز مختلف.',

            // field
            'field.string' => 'حقل التخصص يجب أن يكون نصاً.',
            'field.max'    => 'حقل التخصص طويل جداً، الحد الأقصى 255 حرفاً.',

            // is_active
            'is_active.boolean' => 'حقل الحالة يجب أن يكون نعم أو لا فقط.',

            // type
            'type.required' => 'نوع الدبلومة مطلوب (أونلاين أو حضوري).',
            'type.in'       => 'نوع الدبلومة غير صحيح، يجب أن يكون أونلاين أو حضوري.',

            // details_pdf
            'details_pdf.file'   => 'الملف المرفق يجب أن يكون ملف صالح.',
            'details_pdf.mimes'  => 'الملف المرفق يجب أن يكون بصيغة PDF فقط.',
            'details_pdf.max'    => 'حجم الملف كبير جداً، الحد الأقصى المسموح به 100 ميغابايت.',

            // branch_id
            'branch_id.required' => 'يجب اختيار الفرع المرتبط بهذه الدبلومة.',
            'branch_id.exists'   => 'الفرع المختار غير موجود في النظام، يرجى التحقق.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'اسم الدبلومة',
            'code'        => 'رمز الدبلومة',
            'field'       => 'التخصص',
            'is_active'   => 'الحالة',
            'type'        => 'نوع الدبلومة',
            'details_pdf' => 'ملف التفاصيل',
            'branch_id'   => 'الفرع',
        ];
    }
}