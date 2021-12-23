<?php

namespace App\Http\Requests\user;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
        $user = $this->route('user');

        return [
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users','email')->ignore($user->id),
            ]
        ];
    }
}
