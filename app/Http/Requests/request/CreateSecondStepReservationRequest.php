<?php

namespace App\Http\Requests\request;

use Illuminate\Foundation\Http\FormRequest;

class CreateSecondStepReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'seats' => 'required|array|min:1',
            'seats.*' =>'required|string'
        ];
    }
}
