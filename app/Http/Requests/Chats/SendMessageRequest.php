<?php

namespace App\Http\Requests\Chats;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
            'message'      => 'required_without:attachment|string',
            'recipient_id' => 'required|exists:users,id',
            'attachment'   => 'sometimes|mimes:jpg,png,pdf,xls,xlsx,ppt,doc,docx,mp3,flv,avi,mp4',
        ];
    }

    public function messages()
    {
        return [
            'attachment.mimes' => 'Invalid file',
        ];
    }
}
