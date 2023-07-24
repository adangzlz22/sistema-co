<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceCreateRequest extends FormRequest
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
            'name' => 'min:4|max:20|required',
            'address' => 'min:4|max:150|required'
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
