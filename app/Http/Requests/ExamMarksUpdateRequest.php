<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamMarksUpdateRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'rows' => ['required','array'],
            'rows.*.student_id' => ['required','exists:students,id'],
            'rows.*.components' => ['required','array'],
            'rows.*.components.*.component_id' => ['required','exists:exam_components,id'],
            'rows.*.components.*.score' => ['nullable','numeric','min:0','max:100000'],
            'rows.*.components.*.notes' => ['nullable','string','max:2000'],
        ];
    }
}
