<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuCreateRequest extends FormRequest
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
            'category_id' => 'required',
            'icon_id' => 'required',
            'order' => 'required',
            'permission_id' => 'required_without_all:dropdown',
        ];
    }
/*
    public function messages()
    {
        return [
            'name.required' => 'Se requiere un nombre.',
            'name.min' => 'El nombre debe de ser mayor a 4 caraccteres.',
            'name.max' => 'El nombre debe de ser menor a 120 caraccteres.',
            'category_id.required' => 'Se requiere una categoria.',
            'icon_id.required' => 'Se requiere un icono.',
            'permission_id.required_without_all' => 'Se requiere un permiso.',
        ];
    }*/


}
