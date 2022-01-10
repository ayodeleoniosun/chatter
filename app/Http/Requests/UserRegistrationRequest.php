<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email_address' => 'required|email|unique:users|string',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|min:10|max:15|unique:users'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'Firstname is required',
            'last_name.required' => 'Lastname is required',
            'email_address.required' => 'Email address is required',
            'email_address.unique' => 'Email address already exist',
            'password.required' => 'Password is required',
            'password.min' => 'Password must not be less than 6 characters',
            'phone_number.required' => 'Phone number is required',
            'phone_number.min' => 'Phone number should not be less than 10 characters',
            'phone_number.max' => 'Phone number should not be more than 15 characters',
        ];
    }
}
