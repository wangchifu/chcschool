<?php

namespace App\Http\Controllers;

use App\LunchFactory;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchSetup;
use App\LunchTeaDate;
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

    public function every_day($lunch_order_id=null)
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);

        $lunch_order_array = LunchOrder::orderBy('name','DESC')
            ->pluck('name','id')
            ->toArray();

        $date_array = [];
        $user_data = [];
        $factory_data=[];
        $place_data=[];
        $eat_data=[];
        $days_data=[];
        $money_data=[];
        if($lunch_order_id){
            $lunch_order = LunchOrder::find($lunch_order_id);
            $lunch_setup = LunchSetup::where('semester',$lunch_order->semester)->first();
            $date_array = $this->get_order_date($lunch_order_id);

            $tea_dates = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
                ->where('enable','eat')
                ->orderBy('lunch_place_id')
                ->orderBy('order_date')
                ->get();
            foreach($tea_dates as $tea_date){
                $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;
                $factory_data[$tea_date->user->name][$tea_date->order_date]['name'] = $tea_date->lunch_factory->name;
                $factory_data[$tea_date->user->name][$tea_date->order_date]['id'] = $tea_date->lunch_factory->id;
                if(substr($tea_date->lunch_place_id,0,1)=="c"){
                    $place_data[$tea_date->user->name]=substr($tea_date->lunch_place_id,1,3)."教室";
                }else{
                    $place_data[$tea_date->user->name] = $tea_date->lunch_place->name;
                }
                $eat_data[$tea_date->user->name] = $tea_date->eat_style;
                if($tea_date->enable=="eat"){
                    if(!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name]=0;
                    $days_data[$tea_date->user->name]++;
                    if(!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name]=0;
                    $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
                }
            }

        }

        $data = [
            'lunch_order_id'=>$lunch_order_id,
            'admin'=>$admin,
            'lunch_order_array'=>$lunch_order_array,
            'date_array'=>$date_array,
            'user_data'=>$user_data,
            'factory_data'=>$factory_data,
            'place_data'=>$place_data,
            'eat_data'=>$eat_data,
            'days_data'=>$days_data,
            'money_data'=>$money_data,
        ];
        return view('lunch_lists.every_day',$data);
    }

    public function teacher_money_print($lunch_order_id)
    {
        $lunch_order = LunchOrder::find($lunch_order_id);

        $order_datas = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
            ->where('enable','eat')
            ->orderBy('lunch_place_id')
            ->get();

        $lunch_setup = LunchSetup::where('semester',$lunch_order->semester)->first();

        $user_datas = [];
        $factory_money=[];
        foreach ($order_datas as $order_data) {
            if ($order_data->enable == "eat") {
                if(!isset($user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]=null;
                $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]++;
                $factory_money[$order_data->user->name] = $lunch_setup->teacher_money;
            }
        }

        $data = [
            'lunch_setup'=>$lunch_setup,
            'lunch_order'=>$lunch_order,
            'user_datas' => $user_datas,
            'factory_money' => $factory_money,
        ];
        return view('lunch_lists.teacher_money_print', $data);
    }


    public function all_semester()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);

        $lunch_setup_array = LunchSetup::orderBy('semester','DESC')
            ->pluck('semester','id')
            ->toArray();


        $data = [
            'lunch_setup_array'=>$lunch_setup_array,
            'admin'=>$admin,

        ];
        return view('lunch_lists.all_semester',$data);
    }

    public function semester_print(Request $request)
    {
        if($request->input('submit')=="印出教師全學期收費通知"){
            $lunch_setup = LunchSetup::find($request->input('lunch_setup_id'));

            $order_datas = LunchTeaDate::where('semester',$lunch_setup->semester)
                ->orderBy('lunch_order_id')
                ->orderBy('lunch_place_id')
                ->get();

            $user_datas = [];
            $factory_money=[];
            foreach ($order_datas as $order_data) {
                if ($order_data->enable == "eat") {
                    if(!isset($user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]=null;
                    $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]++;
                    $factory_money[$order_data->user->name][substr($order_data->order_date, 0, 7)] = $lunch_setup->teacher_money;
                }
            }


            $data = [
                'lunch_setup'=>$lunch_setup,
                'user_datas'=>$user_datas,
                'factory_money'=>$factory_money,
                'lunch_setup'=>$lunch_setup,
            ];
            return view('lunch_lists.semester_call_money',$data);
        }

        if($request->input('submit')=="印出教師全學期收據"){
            $lunch_setup = LunchSetup::find($request->input('lunch_setup_id'));

            $order_datas = LunchTeaDate::where('semester',$lunch_setup->semester)
                ->orderBy('lunch_order_id')
                ->orderBy('lunch_place_id')
                ->get();

            $lunch_orders = LunchOrder::where('semester',$lunch_setup->semester)
                ->get();

            $user_datas = [];
            $factory_money=[];
            foreach ($order_datas as $order_data) {
                if ($order_data->enable == "eat") {
                    if(!isset($user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]=null;
                    $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]++;
                    $factory_money = $lunch_setup->teacher_money;
                }
            }

            $data = [
                'lunch_setup'=>$lunch_setup,
                'user_datas'=>$user_datas,
                'factory_money'=>$factory_money,
                'lunch_orders'=>$lunch_orders,
            ];
            return view('lunch_lists.semester_print',$data);
        }

        if($request->input('submit')=="印出廠商全學期收入") {
            $lunch_setup = LunchSetup::find($request->input('lunch_setup_id'));

            /**
            $factories = LunchFactory::where('disable',null)->get();
            foreach($factories as $factory){
                $num = LunchTeaDate::where('semester',$lunch_setup->semester)
                    ->where('lunch_factory_id',$factory->id)
                    ->where('enable','eat')
                    ->count();
                $f[$factory->name]['num'] = $num;
                $f[$factory->name]['money'] = $lunch_setup->teacher_money;

            }
            $data = [
                'semester'=>$lunch_setup->semester,
                'f'=>$f,
            ];*/
            $tea_dates = LunchTeaDate::where('semester',$lunch_setup->semester)
                ->where('enable','eat')
                ->orderBy('lunch_place_id')
                ->get();
            foreach($tea_dates as $tea_date){
                if(!isset($order_data[$tea_date->lunch_factory->name][$tea_date->user->name])) $order_data[$tea_date->lunch_factory->name][$tea_date->user->name]=0;
                $order_data[$tea_date->lunch_factory->name][$tea_date->user->name]++;
            }

            $data = [
                'order_data'=>$order_data,
                'lunch_setup'=>$lunch_setup,
            ];

            return view('lunch_lists.semester_factory',$data);
        }

    }

    public function call_money($lunch_order_id)
    {
        $user_data = [];
        $factory_data=[];
        $days_data=[];
        $money_data=[];

        $lunch_order= LunchOrder::find($lunch_order_id);
        $lunch_setup = LunchSetup::where('semester',$lunch_order->semester)->first();
        $date_array = $this->get_order_date($lunch_order_id);

        $tea_dates = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
            ->where('enable','eat')
            ->orderBy('order_date')
            ->orderBy('lunch_place_id')
            ->get();
        foreach($tea_dates as $tea_date){
            $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;

            if(substr($tea_date->lunch_place_id,0,1)=="c"){
                $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place_id." 教室";
            }else{
                $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place->name;
            }

            $user_data[$tea_date->user->name][$tea_date->order_date]['eat_style'] = $tea_date->eat_style;
            $factory_data[$tea_date->user->name] = $tea_date->lunch_factory->name;
            if($tea_date->enable=="eat"){
                if(!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name]=0;
                $days_data[$tea_date->user->name]++;
                if(!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name]=0;
                $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
            }
        }

        $teacher_money = $lunch_setup->teacher_money;
        $data = [
            'lunch_order'=>$lunch_order,
            'lunch_order_id'=>$lunch_order_id,
            'date_array'=>$date_array,
            'user_data'=>$user_data,
            'factory_data'=>$factory_data,
            'days_data'=>$days_data,
            'money_data'=>$money_data,
            'teacher_money'=>$teacher_money,
        ];
        return view('lunch_lists.call_money', $data);
    }


    public function get_money($lunch_order_id)
    {
        $user_data = [];
        $factory_data=[];
        $days_data=[];
        $money_data=[];

        $lunch_order= LunchOrder::find($lunch_order_id);
        $lunch_setup = LunchSetup::where('semester',$lunch_order->semester)->first();
        $date_array = $this->get_order_date($lunch_order_id);

        $tea_dates = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
            ->where('enable','eat')
            ->orderBy('order_date')
            ->orderBy('lunch_place_id')
            ->get();
        foreach($tea_dates as $tea_date){
            $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;

            if(substr($tea_date->lunch_place_id,0,1)=="c"){
                $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place_id." 教室";
            }else{
                $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place->name;
            }

            $user_data[$tea_date->user->name][$tea_date->order_date]['eat_style'] = $tea_date->eat_style;
            $factory_data[$tea_date->user->name] = $tea_date->lunch_factory->name;
            if($tea_date->enable=="eat"){
                if(!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name]=0;
                $days_data[$tea_date->user->name]++;
                if(!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name]=0;
                $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
            }
        }
        $data = [
            'lunch_order'=>$lunch_order,
            'lunch_order_id'=>$lunch_order_id,
            'date_array'=>$date_array,
            'user_data'=>$user_data,
            'factory_data'=>$factory_data,
            'days_data'=>$days_data,
            'money_data'=>$money_data,
        ];
        return view('lunch_lists.get_money', $data);
    }

    public function more_list_factory($lunch_order_id,$factory_id)
    {
        $factory = LunchFactory::find($factory_id);
        $admin = check_power('午餐系統','A',auth()->user()->id);

        $lunch_order = LunchOrder::find($lunch_order_id);
        $lunch_setup = LunchSetup::where('semester',$lunch_order->semester)->first();

        $lunch_order_array = LunchOrder::orderBy('name','DESC')
            ->pluck('name','id')
            ->toArray();

        $date_array = [];
        $user_data = [];
        //$factory_data=[];
        $place_data=[];
        $eat_data=[];
        $days_data=[];
        $money_data=[];

        $date_array = $this->get_order_date($lunch_order_id);

        $tea_dates = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
            ->where('lunch_factory_id',$factory_id)
            ->where('enable','eat')
            ->orderBy('order_date')
            ->get();
        foreach($tea_dates as $tea_date){
            $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;
            //$user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place->name;
            //$user_data[$tea_date->user->name][$tea_date->order_date]['eat_style'] = $tea_date->eat_style;
            //$factory_data[$tea_date->user->name] = $tea_date->lunch_factory->name;
            if(substr($tea_date->lunch_place_id,0,1)=="c"){
                $place_data[$tea_date->user->name]=substr($tea_date->lunch_place_id,1,3)."教室";
            }else{
                $place_data[$tea_date->user->name] = $tea_date->lunch_place->name;
            }
            $eat_data[$tea_date->user->name] = $tea_date->eat_style;
            if($tea_date->enable=="eat"){
                if(!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name]=0;
                $days_data[$tea_date->user->name]++;
                if(!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name]=0;
                $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
            }
        }


        $data = [
            'lunch_order_id'=>$lunch_order_id,
            'admin'=>$admin,
            'lunch_order_array'=>$lunch_order_array,
            'date_array'=>$date_array,
            'user_data'=>$user_data,
            'factory'=>$factory,
            'place_data'=>$place_data,
            'eat_data'=>$eat_data,
            'days_data'=>$days_data,
            'money_data'=>$money_data,
        ];
        return view('lunch_lists.more_list_factory',$data);
    }


    public function factory(Request $request,$lunch_order_id=null)
    {
        $data = [
        ];

        if($request->input('username')){
            $factory = LunchFactory::where('fid',$request->input('username'))
                ->first();

            /**
            if($request->input('chaptcha') != session('chaptcha')){
                return back()->withErrors(['gsuite_error'=>['驗證碼錯誤！']]);
            }
             * */
            if(empty($factory)){
                if(!$factory) return back()->withErrors(['error'=>['查無此帳號！']]);
            }else{
                if($request->input('password') != $factory->fpwd){
                    return back()->withErrors(['gsuite_error'=>['密碼錯誤！']]);
                }else{
                    session(['factory'=>$factory->fid]);
                };
            }

        }

        if(session('factory')){
            $factory = LunchFactory::where('fid',session('factory'))->first();
            $lunch_order_array = LunchOrder::orderBy('name','DESC')
                ->pluck('name','id')
                ->toArray();

            $data = [
                'factory'=>$factory,
                'lunch_order_id'=>$lunch_order_id,
                'lunch_order_array'=>$lunch_order_array,
            ];

            if($lunch_order_id){
                $date_array = [];
                $user_data = [];
                $factory_data=[];
                $place_data=[];
                $eat_data=[];
                $days_data=[];
                $money_data=[];

                $lunch_order = LunchOrder::find($lunch_order_id);
                $lunch_setup = LunchSetup::where('semester',$lunch_order->semester)->first();


                $date_array = $this->get_order_date($lunch_order_id);

                $tea_dates = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
                    ->where('lunch_factory_id',$factory->id)
                    ->where('enable','eat')
                    ->orderBy('lunch_place_id')
                    ->orderBy('order_date')
                    ->get();
                foreach($tea_dates as $tea_date){
                    $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;
                    if(substr($tea_date->lunch_place_id,0,1)=="c"){
                        $place_data[$tea_date->user->name]=substr($tea_date->lunch_place_id,1,3)."教室";
                    }else{
                        $place_data[$tea_date->user->name] = $tea_date->lunch_place->name;
                    }
                    $eat_data[$tea_date->user->name] = $tea_date->eat_style;
                    if($tea_date->enable=="eat"){
                        if(!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name]=0;
                        $days_data[$tea_date->user->name]++;
                        if(!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name]=0;
                        $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
                    }
                }

                $teacher_money = $lunch_setup->teacher_money;

                $data = [
                    'factory'=>$factory,
                    'lunch_order_id'=>$lunch_order_id,
                    'lunch_order_array'=>$lunch_order_array,
                    'date_array'=>$date_array,
                    'user_data'=>$user_data,
                    'place_data'=>$place_data,
                    'eat_data'=>$eat_data,
                    'days_data'=>$days_data,
                    'money_data'=>$money_data,
                    'teacher_money'=>$teacher_money,
                ];
            }
        }
        return view('lunch_lists.factory',$data);
    }

    public function change_factory()
    {
        session(['factory'=>null]);
        return redirect()->route('lunch_lists.factory');
    }




    public function get_order_date($lunch_order_id)
    {
        $order_dates = LunchOrderDate::where('lunch_order_id',$lunch_order_id)
            ->get();
        foreach($order_dates as $order_date){
            $date_array[$order_date->order_date] = $order_date->enable;
        }
        return $date_array;
    }
}
