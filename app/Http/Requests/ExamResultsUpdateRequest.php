<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamResultsUpdateRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'results' => ['required','array'],
            'results.*.student_id' => ['required','exists:students,id'],
            'results.*.score' => ['nullable','numeric','min:0','max:100000'],
            'results.*.status' => ['required','in:not_set,passed,failed,absent,excused'],
            'results.*.notes' => ['nullable','string','max:2000'],
        ];
    }
}
