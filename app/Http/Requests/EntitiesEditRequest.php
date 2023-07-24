<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntitiesEditRequest extends FormRequest
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
            'acronym' => 'min:2|max:15|required',
            'name' => 'min:4|max:150|required', 
            'holder' => 'min:4|max:150|required',
            'email' => 'min:4|max:150|required',
            'job' => 'min:4|max:150|required',
            'entities_type' => 'required',
            'meeting_type.0' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'meeting_type.0.required' => 'Se requiere por lo menos un tipo de reunión.',
        ];
    }
}
