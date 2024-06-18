<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'job_title' => 'required',
            'files.*' => 'nullable|mimes:csv,txt,zip,jpeg,png,pdf,odt,ods|max:10240',
            'photos.*' => 'nullable|mimes:jpeg,png|max:600000',
        ];
    }

    public function attributes()
    {
        $att = [
            'title_image' => '標題圖片',
            'title' => '標題',
            'content' => '內容',
            'job_title' => '職稱',
        ];

        for($i=0;$i<20;$i++){
            $j = $i+1;
            $att['files.'.$i] = "附件".$j;
        }

        for($i=0;$i<20;$i++){
            $j = $i+1;
            $att['photos.'.$i] = "照片".$j;
        }
        return $att;
    }

    public function messages()
    {
        return [
            'title.required' => ':attribute 不可空白',
            'content.required' => ':attribute 不可空白',
            'job_title.required' => ':attribute 不可空白',
        ];
    }
}
