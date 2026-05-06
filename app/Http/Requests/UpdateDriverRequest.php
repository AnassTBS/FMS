<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
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
        $driverId = $this->route('driver')->id;

        return [
            'name' => 'required|string|max:255',
            'license_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers')->ignore($driverId),
            ],
            'phone' => 'required|string|max:20',
            'status' => 'required|in:available,busy,inactive',
        ];
    }
}
