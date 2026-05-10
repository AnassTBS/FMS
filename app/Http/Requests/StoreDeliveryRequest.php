<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Delivery;

class StoreDeliveryRequest extends FormRequest
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
            'truck_id' => [
                'required',
                'exists:trucks,id',
                function ($attribute, $value, $fail) {
                    $truck = \App\Models\Truck::find($value);
                    if ($truck && $truck->status === 'maintenance') {
                        $fail('The selected truck is in maintenance and cannot be assigned.');
                    }

                    $activeDelivery = Delivery::where('truck_id', $value)
                        ->whereIn('status', [Delivery::STATUS_ASSIGNED, Delivery::STATUS_IN_TRANSIT])
                        ->exists();
                    if ($activeDelivery) {
                        $fail('The selected truck is already assigned to an active delivery.');
                    }
                },
            ],
            'driver_id' => [
                'required',
                'exists:drivers,id',
                function ($attribute, $value, $fail) {
                    $driver = \App\Models\Driver::find($value);
                    if ($driver && $driver->status === 'inactive') {
                        $fail('The selected driver is inactive and cannot be assigned.');
                    }

                    $activeDelivery = Delivery::where('driver_id', $value)
                        ->whereIn('status', [Delivery::STATUS_ASSIGNED, Delivery::STATUS_IN_TRANSIT])
                        ->exists();
                    if ($activeDelivery) {
                        $fail('The selected driver already has an active delivery.');
                    }
                },
            ],
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'status' => ['required', Rule::in(Delivery::statuses())],
            'departure_date' => 'required|date',
            'arrival_date' => [
                'nullable',
                'date',
                'after:departure_date',
                Rule::requiredIf($this->input('status') === Delivery::STATUS_DELIVERED),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'arrival_date.required_if' => 'The arrival date is required when the status is delivered.',
            'arrival_date.after' => 'The arrival date must be after the departure date.',
        ];
    }
}
