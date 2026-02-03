<?php
// app/Http/Requests/StudentStoreRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
{
  public function authorize(): bool { return true; }

  public function rules(): array
  {
    return [
      'first_name' => 'required|string|max:120',
      'last_name'  => 'nullable|string|max:120',
      'full_name'  => 'required|string|max:190',
      'phone'      => 'nullable|string|max:50',
      'whatsapp'   => 'nullable|string|max:120',
      'email'      => 'nullable|email|max:190',

      'branch_id'  => 'required|exists:branches,id',
      'mode'       => 'required|in:onsite,online',
      'status'     => 'required|string',

      'diploma_ids' => 'nullable|array',
      'diploma_ids.*' => 'exists:diplomas,id',

      // nested arrays
      'crm' => 'nullable|array',
      'crm.first_contact_date' => 'nullable|date',
      'crm.residence' => 'nullable|string|max:190',
      'crm.age' => 'nullable|integer|min:1|max:120',
      'crm.organization' => 'nullable|string|max:190',
      'crm.source' => 'nullable|in:ad,referral,social,website,expo,other',
      'crm.need' => 'nullable|string',
      'crm.stage' => 'nullable|in:new,follow_up,interested,registered,rejected,postponed',
      'crm.notes' => 'nullable|string',

      'profile' => 'nullable|array',
      'profile.arabic_full_name' => 'nullable|string|max:190',
      'profile.nationality' => 'nullable|string|max:120',
      'profile.birth_date' => 'nullable|date',
      'profile.national_id' => 'nullable|string|max:120',
      'profile.address' => 'nullable|string|max:255',
      'profile.exam_score' => 'nullable|numeric|min:0|max:999.99',
      'profile.notes' => 'nullable|string',


      'profile.photo' => 'nullable|image|max:2048',
'profile.info_file' => 'nullable|file|max:5120',
'profile.identity_file' => 'nullable|file|max:5120',
'profile.attendance_certificate' => 'nullable|file|max:5120',
'profile.certificate_pdf' => 'nullable|mimes:pdf|max:5120',
'profile.certificate_card' => 'nullable|file|max:5120',


'profile.level' => 'nullable|string|max:100',
'profile.stage_in_state' => 'nullable|string|max:120',
'profile.job' => 'nullable|string|max:120',
'profile.education_level' => 'nullable|string|max:120',
'profile.message_to_send' => 'nullable|string',

    ];
  }
}
