<?php

namespace App\Http\Requests\reservation;

use Illuminate\Foundation\Http\FormRequest;

class ProcessSecondStepReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'seats' => 'required|array|min:1',
            'seats.*' =>'required|string'
        ];
    }
}
