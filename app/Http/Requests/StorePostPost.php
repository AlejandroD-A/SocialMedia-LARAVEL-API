<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostPost extends FormRequest
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
            'title' => 'required | min:5 | max:50',
            'content' => 'required | min:5',
            'cover' => 'mimes:jpeg,bmp,png|max:5000',
            'tags.*.name' => 'min:2 | max:30'
        ];
    }
}
