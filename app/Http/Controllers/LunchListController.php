<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LunchListController extends Controller
{
    public function index()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);
        $data = [
            'admin'=>$admin,
        ];
        return view('lunch_lists.index',$data);
    }
}
