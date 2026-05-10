<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isDispatcher();
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
            'user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', 'driver'),
                Rule::unique('drivers', 'user_id'),
            ],
        ];
    }
}
