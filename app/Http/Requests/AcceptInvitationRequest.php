<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptInvitationRequest extends FormRequest
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
            'token'         => 'required|string|exists:invitations',
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'email_address' => 'required|email|unique:users',
            'password'      => 'required|string|min:6',
            'phone_number'  => 'required|string|min:10|max:15|unique:users'
        ];
    }
}
