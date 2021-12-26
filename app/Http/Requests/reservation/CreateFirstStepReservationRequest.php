<?php

namespace App\Http\Requests\reservation;

use Illuminate\Foundation\Http\FormRequest;
use function auth;

class CreateFirstStepReservationRequest extends FormRequest
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
        $rules = [
            'session' => 'required|exists:sessions,id'
        ];

        if(!auth()->user())
        {
            return array_merge(
                $rules,
                [
                    'name' => 'required|string',
                    'surname' => 'required|string',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:8|confirmed',
                ]
            );
        }

        return $rules;
    }
}
