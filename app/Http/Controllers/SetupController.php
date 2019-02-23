<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_code = school_code();
        $photos = get_files(storage_path('app/public/'.$school_code.'/title_image/random'));
        $data = [
            'school_code'=>$school_code,
            'photos'=>$photos,
        ];
        return view('setups.index',$data);
    }

    public function add_logo(Request $request)
    {
        //新增使用者的上傳目錄
        $school_code = school_code();
        $new_path = 'public/'. $school_code .'/title_image';

        //處理檔案上傳
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');

            $info = [
                'original_filename' => $logo->getClientOriginalName(),
                'extension' => $logo->getClientOriginalExtension(),
            ];

            if($info['extension'] != "png" and $info['extension'] != "ico"){
                return back()->withErrors(['errors'=>'只接受 png 或 ico 檔']);
            }
            $logo->storeAs($new_path, 'logo.ico');

        }
        return redirect()->route('setups.index');
    }

    public function del_img($folder,$filename)
    {
        $school_code = school_code();
        $folder = str_replace('&','/',$folder);
        unlink(storage_path('app/public/'.$school_code.'/'.$folder.'/'.$filename));
        return redirect()->route('setups.index');
    }

    public function add_imgs(Request $request)
    {
        //新增使用者的上傳目錄
        $school_code = school_code();
        $new_path = 'public/'. $school_code .'/title_image/random';

        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $n=1;
            foreach($files as $file){
                $info = [
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ];
                $filename = date("Y-m-d-H-i-s")."-".$n.".".$info['extension'];
                $file->storeAs($new_path, $filename);
                $n++;
            }
        }

        return redirect()->route('setups.index');
    }
}
