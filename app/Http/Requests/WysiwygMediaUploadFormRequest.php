<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WysiwygMediaUploadFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules['file'] = 'mimes:jpeg,jpg,jpe,bmp,png|max:10000';

        return $rules;
    }


    /**
     * Get the messages corresponding to the validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['file.mimes'] = 'Wrong file type.';
        $messages['file.max'] = 'The image size must be less than 10 MB.';

        return $messages;
    }
}
