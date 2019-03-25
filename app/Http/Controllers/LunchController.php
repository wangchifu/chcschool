<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LunchController extends Controller
{
    public function index()
    {
        $data = [

        ];
        return view('lunches.index',$data);
    }
}
