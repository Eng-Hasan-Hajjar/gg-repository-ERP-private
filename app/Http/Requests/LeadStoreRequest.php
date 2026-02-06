<?php

// app/Http/Requests/LeadStoreRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadStoreRequest extends FormRequest
{
  public function authorize(): bool { return true; }

  public function rules(): array
  {
    return [
      'full_name' => 'required|string|max:190',
      'phone' => 'nullable|string|max:50',
      'whatsapp' => 'nullable|string|max:120',
     'email'      => 'nullable|email|max:190',
     'job' => 'nullable|string|max:120',

     
     'profile.job' => 'nullable|string|max:120',
      'first_contact_date' => 'nullable|date',
      'residence' => 'nullable|string|max:190',
      'age' => 'nullable|integer|min:1|max:120',
      'organization' => 'nullable|string|max:190',
      'source' => 'required|in:ad,referral,social,website,expo,other',
      'need' => 'nullable|string',
      'stage' => 'required|in:new,follow_up,interested,registered,rejected,postponed',
      'registration_status' => 'nullable|in:pending,converted,lost',
      'notes' => 'nullable|string',
      'branch_id' => 'required|exists:branches,id',


      'country'  => 'nullable|string|max:100',
'province' => 'nullable|string|max:100',
'study'    => 'nullable|string|max:150',




      'diploma_ids' => 'nullable|array',
      'diploma_ids.*' => 'exists:diplomas,id',
    ];
  }
}
