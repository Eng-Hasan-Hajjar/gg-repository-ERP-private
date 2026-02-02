<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadFollowupStoreRequest extends FormRequest
{
  public function authorize(): bool { return true; }

  public function rules(): array
  {
    return [
      'followup_date' => ['nullable','date'],
      'result' => ['required','string','max:255'],
      'notes' => ['nullable','string'],
    ];
  }
}
