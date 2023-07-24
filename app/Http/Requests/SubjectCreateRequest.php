<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectCreateRequest extends FormRequest
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
            'subject' => 'min:4|max:250|required',
            'expositor' => 'min:4|max:120|required',
            'entity_id' => 'required'
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
