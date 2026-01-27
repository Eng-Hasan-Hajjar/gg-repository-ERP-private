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
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
