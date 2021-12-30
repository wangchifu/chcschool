<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'job_title'=>'required',
            'content' => 'required',
            'files.*' => 'nullable|max:5120',
        ];
    }

    public function attributes()
    {
        $att = [
            'content' => '內容',
            'job_title' => '職稱',
        ];

        for($i=0;$i<20;$i++){
            $j = $i+1;
            $att['files.'.$i] = "附件".$j;
        }
        return $att;
    }

    public function messages()
    {
        return [
            'content.required' => ':attribute 不可空白',
            'job_title.required' => ':attribute 不可空白',
        ];
    }
}
