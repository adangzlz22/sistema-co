<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActionCreateRequest extends FormRequest
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
            'action' => 'min:4|max:500|required',
            'agreement_id' => 'required',
            'entity_id' => 'sometimes|required',
            'files' => 'mimes:zip,rar,pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png,jpeg,tiff,mp3,mp4|max:10000|nullable',
        ];
    }

    public function messages()
    {
        return [
            'files.max' => 'El archivo tiene un tamaño mayor a 10000kb',
            'file.mimes' => 'El archivo tiene un formato diferente a los permitidos(:values)'
        ];
    }
}
