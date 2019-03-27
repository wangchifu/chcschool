<?php

namespace App\Http\Controllers;

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
        $days_data=[];
        $money_data=[];
        if($lunch_order_id){
            $date_array = $this->get_order_date($lunch_order_id);

            $tea_dates = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
                ->orderBy('order_date')
                ->get();
            foreach($tea_dates as $tea_date){
                $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;
                $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place->name;
                $user_data[$tea_date->user->name][$tea_date->order_date]['eat_style'] = $tea_date->eat_style;
                $factory_data[$tea_date->user->name] = $tea_date->lunch_factory->name;
                if($tea_date->enable=="eat"){
                    if(!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name]=0;
                    $days_data[$tea_date->user->name]++;
                    if(!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name]=0;
                    $money_data[$tea_date->user->name] += $tea_date->lunch_factory->teacher_money;
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
            'days_data'=>$days_data,
            'money_data'=>$money_data,
        ];
        return view('lunch_lists.every_day',$data);
    }

    public function teacher_money_print($lunch_order_id)
    {
        $lunch_order = LunchOrder::find($lunch_order_id);

        $order_datas = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
            ->orderBy('user_id')
            ->get();

        $lunch_setup = LunchSetup::where('semester',$lunch_order->semester)->first();

        $user_datas = [];
        $factory_money=[];
        foreach ($order_datas as $order_data) {
            if ($order_data->enable == "eat") {
                if(!isset($user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]=null;
                $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]++;
                $factory_money[$order_data->user->name] = $order_data->lunch_factory->teacher_money;
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





    public function get_order_date($lunch_order_id)
    {
        $order_dates = LunchOrderDate::where('lunch_order_id',$lunch_order_id)
            ->get();
        foreach($order_dates as $order_date){
            $date_array[$order_date->order_date] = $order_date->enable;
        }
        return $date_array;
    }

    public function get_money($lunch_order_id)
    {
        $user_data = [];
        $factory_data=[];
        $days_data=[];
        $money_data=[];

        $date_array = $this->get_order_date($lunch_order_id);

        $tea_dates = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
            ->orderBy('order_date')
            ->get();
        foreach($tea_dates as $tea_date){
            $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;
            $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place->name;
            $user_data[$tea_date->user->name][$tea_date->order_date]['eat_style'] = $tea_date->eat_style;
            $factory_data[$tea_date->user->name] = $tea_date->lunch_factory->name;
            if($tea_date->enable=="eat"){
                if(!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name]=0;
                $days_data[$tea_date->user->name]++;
                if(!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name]=0;
                $money_data[$tea_date->user->name] += $tea_date->lunch_factory->teacher_money;
            }
        }
        $data = [
            'lunch_order_id'=>$lunch_order_id,
            'date_array'=>$date_array,
            'user_data'=>$user_data,
            'factory_data'=>$factory_data,
            'days_data'=>$days_data,
            'money_data'=>$money_data,
        ];
        return view('lunch_lists.get_money', $data);
    }
}
