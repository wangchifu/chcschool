<?php

namespace App\Http\Controllers;

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
        $photos = get_files(storage_path('app/public/'.$school_code.'/title_image/random'));

        $data = [
            'school_code'=>$school_code,
            'photos'=>$photos,
        ];
        return view('index',$data);
    }
}
