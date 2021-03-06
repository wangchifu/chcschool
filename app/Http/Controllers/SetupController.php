<?php

namespace App\Http\Controllers;

use App\Block;
use App\Module;
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
        $request->validate([
            'site_name'=>'required',
            'views'=>['required','numeric'],
        ]);
        $att['site_name'] = $request->input('site_name');
        $att['views'] = $request->input('views');
        $att['footer'] = $request->input('footer');
        $setup->update($att);
        return redirect()->route('setups.index');
    }

    public function col()
    {
        $setup_cols = SetupCol::orderBy('order_by')->get();
        $data = [
            'setup_cols'=>$setup_cols,
        ];
        return view('setups.col',$data);
    }

    public function add_col_table()
    {
        return view('setups.add_col_table');
    }

    public function add_col(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'order_by'=>['nullable','numeric'],
            'num'=>['required','numeric'],
        ]);
        $att['title'] = $request->input('title');
        $att['num'] = $request->input('num');
        $att['order_by'] = $request->input('order_by');
        SetupCol::create($att);
        echo "<body onload='opener.location.reload();window.close();'>";
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
        $request->validate([
            'title'=>'required',
            'order_by'=>['nullable','numeric'],
            'num'=>['required','numeric'],
        ]);
        $att['order_by'] = $request->input('order_by');
        $att['title'] = $request->input('title');
        $att['num'] = $request->input('num');
        $setup_col->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function delete_col(SetupCol $setup_col)
    {
        $att['setup_col_id'] = null;
        Block::where('setup_col_id',$setup_col->id)->update($att);
        $setup_col->delete();
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function block()
    {
        $setup_cols = SetupCol::orderBy('order_by')->get();
        foreach($setup_cols as $setup_col){
            $setup_array[$setup_col->id] = $setup_col->title.'('.$setup_col->id.')';
        }

        $blocks = Block::orderBy('setup_col_id')
            ->orderBy('order_by')
            ->get();
        $data = [
            'setup_array'=>$setup_array,
            'blocks'=>$blocks,
        ];
        return view('setups.block',$data);
    }

    public function add_block_table()
    {
        $setup_cols = SetupCol::orderBy('order_by')->get();
        foreach($setup_cols as $setup_col){
            $setup_array[$setup_col->id] = $setup_col->title.'('.$setup_col->id.')';
        }
        $block_colors = config('chcschool.block_colors');
        $block_colors = array_flip($block_colors);
        $data = [
            'setup_array'=>$setup_array,
            'block_colors'=>$block_colors,
        ];
        return view('setups.add_block_table',$data);
    }

    public function add_block(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'order_by'=>['nullable','numeric'],
        ]);
        $att = $request->all();
        Block::create($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function edit_block(Block $block)
    {
        $setup_cols = SetupCol::orderBy('order_by')->get();
        foreach($setup_cols as $setup_col){
            $setup_array[$setup_col->id] = $setup_col->title.'('.$setup_col->id.')';
        }
        $block_colors = config('chcschool.block_colors');
        $block_colors = array_flip($block_colors);

        $data = [
            'setup_array'=>$setup_array,
            'block'=>$block,
            'block_colors'=>$block_colors,
        ];
        return view('setups.edit_block',$data);
    }

    function update_block(Request $request,Block $block)
    {
        $request->validate([
            'title'=>'required',
            'order_by'=>['nullable','numeric'],
        ]);
        $att = $request->all();
        $block->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function delete_block(Block $block)
    {
        $block->delete();
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function block_color()
    {
        return view('setups.block_color');
    }

    public function module()
    {
        $modules = config('chcschool.modules');
        $data = [
            'modules'=>$modules,
        ];
        return view('setups.module',$data);
    }

    public function update_module(Request $request)
    {
        $modules = Module::orderBy('id')->get();
        foreach($modules as $m){
            $m->delete();
        }


        $module = $request->input('module');

        foreach($module as $k=>$v){
            $att['name'] = $k;
            $att['active'] = $v;
            Module::create($att);
        }
        return redirect()->route('setups.module');
    }
}
