<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPasswordRequest extends FormRequest
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
            'password' => 'required|min:8|confirmed',
        ];

        if (!auth()->user()->isAdmin() || $this->route('user') === auth()->user())
        {
            $rules['password_old'] = 'required|min:8';
        }

        return $rules;
    }
}
