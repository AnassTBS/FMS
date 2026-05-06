<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:drivers,license_number|max:255',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:available,busy,inactive',
        ];
    }
}
