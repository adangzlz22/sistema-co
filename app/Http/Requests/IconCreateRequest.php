<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IconCreateRequest extends FormRequest
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
            'name' => 'min:4|max:120|required',
            'key' => 'required'
        ];
    }

  /*  public function messages()
    {
        return [
            'name.required' => 'Se requiere un nombre.',
            'key.required' => 'Se requiere una clave.',
        ];
    }*/


}
