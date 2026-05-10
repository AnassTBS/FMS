<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Delivery;

class UpdateDeliveryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()?->isAdmin() || $this->user()?->isDispatcher()) {
            return true;
        }

        $delivery = $this->route('delivery');

        return $this->user()?->isDriver()
            && $this->user()?->driver?->id === $delivery?->driver_id;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->user()?->isDriver()) {
            return [
                'status' => ['required', Rule::in(Delivery::statuses())],
            ];
        }

        $deliveryId = $this->route('delivery')->id;

        return [
            'truck_id' => [
                'required',
                'exists:trucks,id',
                function ($attribute, $value, $fail) use ($deliveryId) {
                    $truck = \App\Models\Truck::find($value);
                    if ($truck && $truck->status === 'maintenance') {
                        $fail('The selected truck is in maintenance and cannot be assigned.');
                    }

                    $activeDelivery = Delivery::where('truck_id', $value)
                        ->whereIn('status', [Delivery::STATUS_ASSIGNED, Delivery::STATUS_IN_TRANSIT])
                        ->where('id', '!=', $deliveryId)
                        ->exists();
                    if ($activeDelivery) {
                        $fail('The selected truck is already assigned to another active delivery.');
                    }
                },
            ],
            'driver_id' => [
                'required',
                'exists:drivers,id',
                function ($attribute, $value, $fail) use ($deliveryId) {
                    $driver = \App\Models\Driver::find($value);
                    if ($driver && $driver->status === 'inactive') {
                        $fail('The selected driver is inactive and cannot be assigned.');
                    }

                    $activeDelivery = Delivery::where('driver_id', $value)
                        ->whereIn('status', [Delivery::STATUS_ASSIGNED, Delivery::STATUS_IN_TRANSIT])
                        ->where('id', '!=', $deliveryId)
                        ->exists();
                    if ($activeDelivery) {
                        $fail('The selected driver already has another active delivery.');
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
