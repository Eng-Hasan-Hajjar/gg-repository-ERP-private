<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentExtraUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('students.extra.update');
    }

    public function rules(): array
    {
        return [
            'data' => ['nullable','array'],
            // أمثلة حقول داخل data:
            'data.address' => ['nullable','string','max:255'],
            'data.birth_date' => ['nullable','date'],
            'data.nationality' => ['nullable','string','max:100'],
            'data.notes' => ['nullable','string','max:2000'],
        ];
    }
}
