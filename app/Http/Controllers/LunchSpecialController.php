<?php

namespace App\Http\Controllers;

use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchTeaDate;
use App\User;
use Illuminate\Http\Request;

class LunchSpecialController extends Controller
{
    public function index()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);
        $data = [
            'admin'=>$admin,
        ];
        return view('lunch_specials.index',$data);
    }

    public function one_day()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);
        $data = [
            'admin'=>$admin,
        ];
        return view('lunch_specials.one_day',$data);
    }

    public function one_day_store(Request $request)
    {
        if($request->input('action')=="not_eat"){
            $lunch_order_date = LunchOrderDate::where('order_date',$request->input('order_date'))
                ->first();
            $att['enable'] = 0;
            if($lunch_order_date){
                $lunch_order_date->update($att);
            }
            $att2['enable'] = "not_eat";
            LunchTeaDate::where('order_date',$request->input('order_date'))
                ->update($att2);
        }elseif($request->input('action')=="eat"){
            $lunch_order_date = LunchOrderDate::where('order_date',$request->input('order_date'))
                ->first();
            $att['enable'] = 1;
            if($lunch_order_date){
                $lunch_order_date->update($att);
            }

        }

        return redirect()->route('lunch_specials.one_day');
    }

    public function late_teacher()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);

        $user_array = User::where('disable',null)
            ->where('username','<>','admin')
            ->orderBy('order_by')
            ->pluck('name','id')
            ->toArray();

        $lunch_order_array = LunchOrder::orderBy('name','DESC')
            ->pluck('name','id')
            ->toArray();
        $data = [
            'admin'=>$admin,
            'user_array'=>$user_array,
            'lunch_order_array'=>$lunch_order_array,
        ];
        return view('lunch_specials.late_teacher',$data);
    }
}
