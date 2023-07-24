<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectFilesRequest extends FormRequest
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
            'file' => 'mimes:zip,rar,pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png,tiff|max:10000|required',
        ];
    }

//    public function messages()
//    {
//        return [
//            'comments.required' => 'Se requiere un comentario.',
//            'file.mimes' => 'El archivo tiene un formato diferente a los permitidos: :values',
//            'file.max' => 'El archivo tiene un tamaño mayor a 10000kb'
//        ];
//    }
}
