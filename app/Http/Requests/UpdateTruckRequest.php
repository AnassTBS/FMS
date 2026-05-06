<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTruckRequest extends FormRequest
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
        $truckId = $this->route('truck')->id;

        return [
            'registration_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('trucks')->ignore($truckId),
            ],
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,in_use,maintenance',
        ];
    }
}
