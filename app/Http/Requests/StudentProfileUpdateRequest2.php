<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('students.extra.update');
    }

    public function rules(): array
    {
        return [
            'arabic_full_name' => ['nullable','string','max:255'],
            'national_id'      => ['nullable','string','max:50'],
            'birth_date'       => ['nullable','date'],
            'nationality'      => ['nullable','string','max:100'],

            'address'          => ['nullable','string','max:255'],
            'location'         => ['nullable','string','max:255'],

            'stage'            => ['nullable','string','max:100'],
            'work'             => ['nullable','string','max:150'],
            'education_level'  => ['nullable','string','max:150'],

            'exam_score'       => ['nullable','numeric','min:0','max:100'],
            'notes'            => ['nullable','string','max:5000'],

            // ملفات
            'photo'            => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'info_file'        => ['nullable','file','mimes:pdf,doc,docx','max:5120'],
            'identity_file'    => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
            'certificate_pdf'  => ['nullable','file','mimes:pdf','max:5120'],

            // Flags
            'has_attendance_certificate' => ['nullable','boolean'],
            'has_certificate_card'       => ['nullable','boolean'],

            'message_to_student' => ['nullable','string','max:5000'],
        ];
    }
}
