<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgreementsCreateRequest extends FormRequest
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
            'agreement' => 'required',
            'entity_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //'name.required' => 'Se requiere un nombre.',
            //'name.min' => 'El nombre debe de ser mayor a 4 caraccteres.',
            //'name.max' => 'El nombre debe de ser menor a 120 caraccteres.',
            'description.max' => 'La descripción debe de ser menor a 1000 caraccteres.',
            'description.required' => 'Se requiere una descripción.',
            'date.required' => 'Se requiere de la fecha del acuerdo.',
            'organisms.required' => 'Se requiere de un organismo.',
            'document.mimes' => 'El archivo tiene un formato diferente a los permitidos: doc, dox y pdf',
            'document.max' => 'El archivo tiene un tamaño mayor a 10000kb'
        ];
    }


}
