<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionsStoreRequest extends FormRequest
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
            'name' => 'unique:permissions,id,'.$this->id.'|required|max:120',
            'group_permission_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'El nombre de la categoría ya ha sido registrado.',
           /* 'name.required' => 'El nombre de la categoría es requerido.',
            'name.max' => 'El nombre de la categoría tiene un máximo de :max caracteres.',
            'group_permission_id.required' => 'Se requiere un grupo.',*/
        ];
    }
}
