<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingCreateRequest extends FormRequest
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
            'meeting_type' => 'required',
            'date' => 'required',
            'time_start' => 'required',
            'time_start' => 'required',
            'modality' => 'required',
            'place' => 'required_if:modality,1,3',
            'link' => 'required_if:modality,2,3'
        ];
    }

   /* public function messages()
    {
        return [
            'name.required' => 'Se requiere un nombre.',
            'description.required' => 'Se requiere una descripción.',
        ];
    }*/
}
