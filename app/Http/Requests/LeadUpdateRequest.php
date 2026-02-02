<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadUpdateRequest extends FormRequest
{
  public function authorize(): bool { return true; }

  public function rules(): array
  {
    return [
      'full_name' => ['required','string','max:255'],
      'phone'     => ['nullable','string','max:50'],
      'whatsapp'  => ['nullable','string','max:255'],

      'first_contact_date' => ['nullable','date'],
      'residence' => ['nullable','string','max:255'],
      'age' => ['nullable','integer','min:1','max:120'],
      'organization' => ['nullable','string','max:255'],

      'source' => ['required','in:ad,referral,social,website,expo,other'],
      'need'   => ['nullable','string'],

      'stage'  => ['required','in:new,follow_up,interested,registered,rejected,postponed'],
      'registration_status' => ['required','in:pending,converted,lost'],
      'registered_at' => ['nullable','date'],
      'notes'  => ['nullable','string'],

      'branch_id' => ['required','exists:branches,id'],

      'diploma_ids' => ['required','array','min:1'],
      'diploma_ids.*' => ['integer','exists:diplomas,id'],
      'primary_diploma_id' => ['nullable','integer','exists:diplomas,id'],
    ];
  }
}
