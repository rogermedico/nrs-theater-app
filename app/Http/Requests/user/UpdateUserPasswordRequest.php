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
        $rules = [
            'password' => 'required|min:8|confirmed',
        ];

        if (!auth()->user()->isAdmin() || $this->route('user')->id === auth()->user()->id) {
            $rules['password_old'] = 'required|min:8';
        }

        return $rules;
    }
}
