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
            'name'      => ['required','string','max:255'],
            'code'      => ['required','string','max:50', 'alpha_dash', Rule::unique('diplomas','code')->ignore($diplomaId)],
            'field'     => ['nullable','string','max:255'],
            'is_active' => ['nullable','boolean'],
            'type' => ['required','in:online,onsite'],
            'details_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:102400'],
                'branch_id'   => 'required|exists:branches,id',
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
            'branch_id.required' => 'يجب اختيار الفرع.',
            'branch_id.exists'   => 'الفرع المختار غير موجود.',
        ];
    }
}
