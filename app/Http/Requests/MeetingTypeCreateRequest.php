<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingTypeCreateRequest extends FormRequest
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
            'acronym' => ['required', 'unique:meeting_types'],
            'name' => ['min:4|max:150|required', 'unique:meeting_types'],
            'color' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'acronym.unique' => 'Esta sigla ya se encuentra registrada en el sistema.',
            'name.unique' => 'Este nombre ya se encuentra registrado en el sistema.'
        ];
    }
}
