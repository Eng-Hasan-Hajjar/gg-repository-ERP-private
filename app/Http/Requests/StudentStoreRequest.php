<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
{
 

    public function rules(): array
    {
        return [
            'first_name' => ['required','string','max:100'],
            'last_name'  => ['required','string','max:100'],
            'full_name'  => ['required','string','max:200'],

            'diploma_name' => ['nullable','string','max:150'],
            'diploma_code' => ['nullable','string','max:50'],
            'level'        => ['nullable','string','max:50'],

            'phone'      => ['nullable','string','max:30'],
            'whatsapp'   => ['nullable','string','max:255'],
            'email'      => ['nullable','email','max:150'],

            'branch_id'  => ['required','exists:branches,id'],
            'mode'       => ['required','in:onsite,online'],

            // الحالة الأكاديمية
            'status'     => ['required','in:active,waiting,paid,withdrawn,failed,absent_exam,certificate_delivered,certificate_waiting,registration_ended,dismissed,frozen'],

            // حالة التسجيل (تبقى pending عند الإنشاء)
            'registration_status' => ['nullable','in:pending,confirmed,archived,dismissed,frozen'],
        ];
    }
}
