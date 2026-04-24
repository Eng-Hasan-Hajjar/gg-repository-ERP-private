<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamStoreRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'title'      => ['required','string','max:200'],
            'code'       => ['nullable','string','max:50','unique:exams,code'],
            'exam_date'  => ['nullable','date'],
            'type'       => ['required','in:quiz,midterm,final,practical,other'],
            'max_score'  => ['required','numeric','min:0','max:100000'],
            'pass_score' => ['nullable','numeric','min:0','max:100000'],
            'diploma_id' => ['required','exists:diplomas,id'],
            'branch_id'  => ['nullable','exists:branches,id'],
            'trainer_id' => ['nullable','exists:employees,id'],
            'notes'      => ['nullable','string','max:5000'],
        ];
    }



     public function messages(): array
    {
        return [
            'title.required' => 'اسم الامتحان مطلوب.',
            'title.string' => 'اسم الامتحان يجب أن يكون نصاً.',
            'title.max' => 'اسم الامتحان لا يتجاوز 200 حرف.',
            
            'code.max' => 'الكود لا يتجاوز 50 حرف.',
            'code.unique' => 'هذا الكود مستخدم بالفعل من قبل امتحان آخر.',
            
            'exam_date.date' => 'تاريخ الامتحان يجب أن يكون تاريخاً صالحاً.',
            
            'type.required' => 'نوع الامتحان مطلوب.',
            'type.in' => 'نوع الامتحان غير صالح.',
            
            'max_score.required' => 'الحد الأعلى للدرجة مطلوب.',
            'max_score.numeric' => 'الحد الأعلى يجب أن يكون رقماً.',
            'max_score.min' => 'الحد الأعلى لا يمكن أن يكون أقل من 0.',
            'max_score.max' => 'الحد الأعلى لا يتجاوز 100000.',
            
            'pass_score.numeric' => 'حد النجاح يجب أن يكون رقماً.',
            'pass_score.min' => 'حد النجاح لا يمكن أن يكون أقل من 0.',
            'pass_score.max' => 'حد النجاح لا يتجاوز 100000.',
            
            'diploma_id.required' => 'الرجاء اختيار الدبلومة.',
            'diploma_id.exists' => 'الدبلومة المحددة غير موجودة.',
            
            'branch_id.exists' => 'الفرع المحدد غير موجود.',
            
            'trainer_id.exists' => 'المدرب المحدد غير موجود.',
            
            'notes.max' => 'الملاحظات لا تتجاوز 5000 حرف.',
        ];
    }



    
}
