<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // دعم تعديل بدون تكرار الكود
        $id = $this->route('asset_category')?->id;

        return [
            'name' => ['required','string','max:255'],
            'code' => ['required','string','max:50','unique:asset_categories,code,' . ($id ?? 'NULL')],
        ];
    }
}
