<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageFormRequest extends FormRequest
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
        return [
            'title' => 'nullable|string|max:255]',
            'name' => 'required|regex:/^[a-z]*$/u',
            'metadescription' => 'nullable|min:2',
            'body' => 'required',
            'video' => 'max:11000',
        ];
    }
}
