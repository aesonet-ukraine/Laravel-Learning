<?php

namespace App\Http\Requests\Auth;

use App\Rules\Password;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            ...parent::messages(),
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 3 characters',
            'name.max' => 'Name must not exceed 30 characters',
            'surname.required' => 'Surname is required',
            'surname.min' => 'Surname must be at least 3 characters',
            'surname.max' => 'Surname must not exceed 50 characters',
            'phone.required' => 'Phone is required',
            'phone.max' => 'Phone must not exceed 15 characters',
            'phone.unique' => 'Phone is already exist',
            'email.required' => 'Email is required',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'Email is already exist',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:30'],
            'surname' => ['required', 'string', 'min:2', 'max:50'],
            'phone' => ['required', 'string', 'max:15', 'unique:users,phone', new PhoneNumber],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', new Password],
        ];
    }
}
