<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'phone' => 'numeric|digits:10|nullable',
            'is_signer' => 'nullable',
            'password' => 'nullable|min:8|max:20|confirmed',
            'password_confirmation' => '',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'role' => 'sometimes|required',
        ];
    }

    public function messages()
    {
        return [
            /*'name.required' => 'Se requiere un nombre.',
            //'last_name.required' => 'Se requiere un apellido paterno.',
            'email.required' => 'Se requiere un correo electrónico.',
            //'password.required' => 'Se requiere una contraseña.',
            'password_confirmation.required' => 'Se requiere que confirme su contraseña.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            //'last_name.min' => 'El apellido paterno debe tener al menos :min caracteres.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',*/
            'phone.numeric' => 'El teléfono debe ser numérico',
            'phone.digits' => 'El teléfono debe ser de 10 digitos',
            'email.unique' => 'El correo electrónico ya ha sido registrado anteriormente.',
            'email.email' => 'El correo electrónico no tiene un formato correcto.',
            /*'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'au_is_required' => 'Se debe de seleccionar un sub organismo.',
            'org_is_required' => 'Se debe de seleccionar un organismo.',*/
        ];
    }
}
