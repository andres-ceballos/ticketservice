<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateIncidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->isUser()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|between:1,255',
            'message_reply' => 'required|string|between:1,255',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El campo título es obligatorio.',
            'message_reply.required' => 'El campo mensaje es obligatorio.',
        ];
    }
}
