<?php

namespace App\Http\Controllers;

use App\Block;
use App\SetupCol;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_code = school_code();
        $files = get_files(storage_path('app/public/'.$school_code.'/title_image/random'));
        if($files) {
            foreach ($files as $k=>$v) {
                $photos[$k] = asset('storage/'.$school_code.'/title_image/random/'.$v);
            }
        }else{
            $photos = [
                '0'=>asset('images/top0.svg'),
                '1'=>asset('images/top1.svg'),
                '2'=>asset('images/top2.svg'),
            ];
        }

        $setup = \App\Setup::find(1);

        $setup_cols = SetupCol::all();
        foreach($setup_cols as $setup_col){
            $bs = Block::where('setup_col_id',$setup_col->id)
                ->orderBy('order_by')
                ->get();

            $blocks[$setup_col->id] = $bs;

        }
        $data = [
            'school_code'=>$school_code,
            'photos'=>$photos,
            'setup'=>$setup,
            'setup_cols'=>$setup_cols,
            'blocks'=>$blocks,
        ];
        return view('index',$data);
    }

    public function edit_password()
    {
        return view('edit_password');
    }

    public function update_password(Request $request)
    {

        if(!password_verify($request->input('password0'), auth()->user()->password)){
            return back()->withErrors(['error'=>['舊密碼錯誤！你不是本人！？']]);
        }
        if($request->input('password1') != $request->input('password2')){
            return back()->withErrors(['error'=>['兩次新密碼不相同']]);
        }


        $att['id'] = auth()->user()->id;
        $att['password'] = bcrypt($request->input('password1'));
        $user = User::where('id',$att['id'])->first();
        $user->update($att);
        return redirect()->route('index');
    }

    public function getFile($file)
    {
        $file = str_replace('&','/',$file);
        $file = storage_path('app/privacy/'.$file);
        return response()->download($file);
    }
}
