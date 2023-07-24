<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntitiesCreateRequest extends FormRequest
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
            'acronym' => ["min:2", "max:150", 'required', 'unique:entities'],
            'name' => ["min:4", "max:150", 'required', 'unique:entities'],
            'holder' => 'min:4|max:150|required',
            'email' => ["min:4", "max:150",'required', 'unique:entities'],
            'job' => 'min:4|max:150|required',
            'entities_type' => 'required',
            'meeting_type.0' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'meeting_type.0.required' => 'Se requiere por lo menos un tipo de reunión.',
            'acronym.unique' => 'Esta sigla ya se encuentra registrada en el sistema.',
            'name.unique' => 'Este nombre ya se encuentra registrado en el sistema.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado en el sistema.',
        ];
    }
}
