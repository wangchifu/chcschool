<?php

namespace App\Http\Controllers;

use App\LunchFactory;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchPlace;
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
            }else{
                return back()->withErrors(['error'=>['查無該日期資料！']]);
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
            }else{
                return back()->withErrors(['error'=>['查無該日期資料！']]);
            }

        }

        return redirect()->route('lunch_specials.index');
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

    public function teacher_change_month()
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

        $lunch_factory_array = LunchFactory::where('disable',null)
            ->pluck('name','id')
            ->toArray();
        $lunch_place_array = LunchPlace::where('disable',null)
            ->pluck('name','id')
            ->toArray();

        $eat_array = [
            '1'=>'葷食合菜',
            '2'=>'素食合菜',
            '3'=>'葷食便當',
            '4'=>'素食便當',
        ];

        $data = [
            'admin'=>$admin,
            'user_array'=>$user_array,
            'lunch_order_array'=>$lunch_order_array,
            'lunch_factory_array'=>$lunch_factory_array,
            'lunch_place_array'=>$lunch_place_array,
            'eat_array'=>$eat_array,
        ];
        return view('lunch_specials.teacher_change_month',$data);
    }

    public function teacher_update_month(Request $request)
    {
        $user_id = $request->input('user_id');
        $lunch_order_id = $request->input('lunch_order_id');
        $att['lunch_factory_id'] = $request->input('lunch_factory_id');
        if($request->input('select_place')=="place_select"){
            $lunch_place_id = $request->input('lunch_place_id');
        }elseif($request->input('select_place')=="place_class"){
            $lunch_place_id = "c".$request->input('class_no');
        }
        $att['lunch_place_id'] = $lunch_place_id;
        $att['eat_style'] = $request->input('eat_style');
        LunchTeaDate::where('user_id',$user_id)
            ->where('lunch_order_id',$lunch_order_id)
            ->update($att);
        return redirect()->route('lunch_specials.index');
    }

    public function late_teacher_show(Request $request)
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);

        //是否已經訂過
        $count = LunchTeaDate::where('user_id',$request->input('user_id'))
            ->where('lunch_order_id',$request->input('lunch_order_id'))
            ->count();
        if($count){
            return back()->withErrors(['error'=>['該員該期已訂過餐！']]);
        }



        $lunch_factory_array = [];
        $lunch_place_array = [];

        $lunch_order = LunchOrder::find($request->input('lunch_order_id'));
        $user = User::find($request->input('user_id'));

        $factories = LunchFactory::where('disable',null)
            ->get();
        foreach($factories as $factory){
            $lunch_factory_array[$factory->id] = $factory->name.' ($'.$factory->teacher_money.') ';
        }
        $lunch_place_array = LunchPlace::where('disable',null)
            ->pluck('name','id')
            ->toArray();
        $eat_styles = ['1'=>'葷食','2'=>'素食'];

        $data = [
            'admin'=>$admin,
            'user'=>$user,
            'lunch_order'=>$lunch_order,
            'lunch_factory_array'=>$lunch_factory_array,
            'lunch_place_array'=>$lunch_place_array,
            'eat_styles'=>$eat_styles,
        ];

        return view('lunch_specials.late_teacher_show',$data);

    }

    public function late_teacher_store(Request $request)
    {
        $semester = $request->input('semester');
        $lunch_factory_id = $request->input('lunch_factory_id');
        $lunch_place_id = $request->input('lunch_place_id');
        $eat_style = $request->input('eat_style');
        $lunch_order_id = $request->input('lunch_order_id');
        $user_id = $request->input('user_id');
        $lunch_order = LunchOrder::find($lunch_order_id);

        $order_date = $request->input('order_date');

        $all = [];
        foreach ($lunch_order->lunch_order_dates as $lunch_order_date) {
            if (isset($order_date[$lunch_order_date->order_date])) {
                $enable = "eat";
            } else {
                $enable = "not_eat";
            }
            $one = [
                'order_date'=>$lunch_order_date->order_date,
                'enable'=>$enable,
                'semester'=>$semester,
                'lunch_order_id'=>$lunch_order_id,
                'user_id'=>$user_id,
                'lunch_place_id'=>$lunch_place_id,
                'lunch_factory_id'=>$lunch_factory_id,
                'eat_style'=>$eat_style,
                'created_at'=>now(),
                'updated_at'=>now(),
            ];
            array_push($all,$one);
        }

        LunchTeaDate::insert($all);

        return redirect()->route('lunch_specials.index');
    }

    public function teacher_change()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);
        $user_array = User::where('disable',null)
            ->where('username','<>','admin')
            ->orderBy('order_by')
            ->pluck('name','id')
            ->toArray();
        $data = [
            'admin'=>$admin,
            'user_array'=>$user_array,
        ];
        return view('lunch_specials.teacher_change',$data);
    }

    public function teacher_change_store(Request $request)
    {
        $lunch_tea_date = LunchTeaDate::where('order_date',$request->input('order_date'))
            ->where('user_id',$request->input('user_id'))
            ->first();
        if($lunch_tea_date){
            $att['enable'] = $request->input('action');
            $lunch_tea_date->update($att);
        }else{
            return back()->withErrors(['error'=>['查無該日期資料！']]);
        }

        return redirect()->route('lunch_specials.index');
    }
}
