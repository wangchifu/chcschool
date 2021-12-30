<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'title_image' => 'nullable|mimes:jpeg,png|max:10240',
            'title' => 'required',
            'content' => 'required',
        ];
    }

    public function attributes()
    {
        $att = [
            'title_image' => '標題圖片',
            'title' => '標題',
            'content' => '內容',
        ];

        return $att;
    }

    public function messages()
    {
        return [
            'title.required' => ':attribute 不可空白',
            'content.required' => ':attribute 不可空白',
        ];
    }
}
