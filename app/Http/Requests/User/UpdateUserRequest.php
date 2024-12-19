<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'=>['sometimes','email','string',Rule::unique('users','email')->ignore($this->route()->parameter('id'))],
            'name'=>['sometimes','string'],
            'phone'=>['sometimes','digits:10',Rule::unique('users','phone')->ignore($this->route()->parameter('id'))],
        ];
    }
}
