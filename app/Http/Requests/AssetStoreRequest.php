<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // اربطها بالصلاحيات لاحقاً
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:5000'],
            'asset_category_id' => ['nullable','exists:asset_categories,id'],
            'branch_id' => ['nullable','exists:branches,id'],
            'condition' => ['required','in:good,maintenance,out_of_service'],
            'purchase_date' => ['nullable','date'],
            'purchase_cost' => ['nullable','numeric','min:0'],
            'currency' => ['required','string','size:3'],
            'serial_number' => ['nullable','string','max:255'],
            'location' => ['nullable','string','max:255'],

            // رفع صورة
            'photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ];
    }
}
