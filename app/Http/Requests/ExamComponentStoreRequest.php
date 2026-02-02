<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamComponentStoreRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'title' => ['required','string','max:200'],
            'key' => ['nullable','string','max:50'],
            'max_score' => ['required','numeric','min:0','max:100000'],
            'weight' => ['required','numeric','min:0','max:100'],
            'is_required' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0','max:100000'],
        ];
    }
}
