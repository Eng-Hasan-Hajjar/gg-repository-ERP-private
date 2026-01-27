<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $branchId = $this->route('branch')->id;

        return [
            'name' => ['required','string','max:255'],
            'code' => ['required','string','max:30', 'alpha_dash', Rule::unique('branches','code')->ignore($branchId)],
        ];
    }
}
