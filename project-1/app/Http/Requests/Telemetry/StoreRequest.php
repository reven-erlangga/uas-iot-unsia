<?php

namespace App\Http\Requests\Telemetry;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'station_id' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'device_id' => 'required|string',
            'device_name' => 'nullable|string',
            'metadata' => 'nullable|array',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'station_id.required' => 'Station ID is required',
            'station_id.string' => 'Station ID must be a string',
            'temperature.required' => 'Temperature is required',
            'temperature.numeric' => 'Temperature must be a number',
            'humidity.required' => 'Humidity is required',
            'humidity.numeric' => 'Humidity must be a number',
            'device_id.required' => 'Device ID is required',
            'device_id.string' => 'Device ID must be a string',
            'metadata.json' => 'Metadata must be a JSON string',
        ];
    }

}
