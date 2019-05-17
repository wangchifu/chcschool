<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsideFileRequest extends FormRequest
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
            'files.*' => 'required|max:10240',
        ];
    }

    public function attributes()
    {

        for($i=0;$i<20;$i++){
            $j = $i+1;
            $att['files.'.$i] = "檔案".$j;
        }
        return $att;
    }
}
