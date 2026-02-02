<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamUpdateRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $examId = $this->route('exam')?->id;

        return [
            'title'      => ['required','string','max:200'],
            'code'       => ['nullable','string','max:50',"unique:exams,code,{$examId}"],
            'exam_date'  => ['nullable','date'],
            'type'       => ['required','in:quiz,midterm,final,practical,other'],
            'max_score'  => ['required','numeric','min:0','max:100000'],
            'pass_score' => ['nullable','numeric','min:0','max:100000'],
            'diploma_id' => ['required','exists:diplomas,id'],
            'branch_id'  => ['required','exists:branches,id'],
            'trainer_id' => ['nullable','exists:employees,id'],
            'notes'      => ['nullable','string','max:5000'],
        ];
    }
}
