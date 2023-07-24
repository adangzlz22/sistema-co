<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyActionCreateRequest extends FormRequest
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
            'reply' => 'min:4|max:500|required',
            'action_id' => 'required',
            'files.*' => 'mimes:pdf,doc,docx,csv,xls,xlsx,ppt,pptx,jpg,png,jpeg|max:10000|nullable',
        ];
    }

    public function messages()
    {
        return [
            'files*.max' => 'El archivo tiene un tamaño mayor a 10000kb',
            'files*.mimes' => 'El archivo tiene un formato diferente a los permitidos(:values)'
        ];
    }
}
