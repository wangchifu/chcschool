<?php

namespace App\Http\Controllers;

use App\Setup;
use App\SetupCol;
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
        $setup = Setup::first();
        $data = [
            'setup'=>$setup,
        ];
        return view('setups.index',$data);
    }

    public function photo()
    {
        $school_code = school_code();
        $setup = Setup::first();
        $photos = get_files(storage_path('app/public/'.$school_code.'/title_image/random'));
        $data = [
            'school_code'=>$school_code,
            'setup'=>$setup,
            'photos'=>$photos,
        ];
        return view('setups.photo',$data);
    }

    public function update_title_image(Request $request,Setup $setup)
    {
        $att['title_image'] = $request->input('title_image');
        $setup->update($att);
        return redirect()->route('setups.photo');
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
        return redirect()->route('setups.photo');
    }

    public function del_img($folder,$filename)
    {
        $school_code = school_code();
        $folder = str_replace('&','/',$folder);
        unlink(storage_path('app/public/'.$school_code.'/'.$folder.'/'.$filename));
        return redirect()->route('setups.photo');
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

        return redirect()->route('setups.photo');
    }

    public function nav_color(Request $request,Setup $setup)
    {
        $nav_color = $request->input('color');
        $att['nav_color'] = "";
        foreach($nav_color as $v){
            $att['nav_color'] .= $v.",";
        }
        $setup->update($att);
        return redirect()->route('setups.index');
    }

    public function nav_default()
    {
        $setup = Setup::first();
        $att['nav_color'] = null;
        $setup->update($att);
        return redirect()->route('setups.index');
    }

    public function text(Request $request,Setup $setup)
    {
        $att['site_name'] = $request->input('site_name');
        $att['views'] = $request->input('views');
        $setup->update($att);
        return redirect()->route('setups.index');
    }

    public function col()
    {
        $setup_cols = SetupCol::all();
        $data = [
            'setup_cols'=>$setup_cols,
        ];
        return view('setups.col',$data);
    }

    public function add_col(Request $request)
    {
        $att['num'] = $request->input('num');
        SetupCol::create($att);
        return redirect()->route('setups.col');
    }

    public function edit_col(SetupCol $setup_col)
    {
        $data = [
            'setup_col'=>$setup_col,
        ];
        return view('setups.edit_col',$data);
    }

    public function update_col(Request $request,SetupCol $setup_col)
    {
        $att['num'] = $request->input('num');
        $setup_col->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function delete_col(SetupCol $setup_col)
    {
        $setup_col->delete();
        echo "<body onload='opener.location.reload();window.close();'>";
    }
}
