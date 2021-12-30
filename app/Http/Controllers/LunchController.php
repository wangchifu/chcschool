<?php

namespace App\Http\Controllers;

use App\LunchFactory;
use App\LunchOrder;
use App\LunchPlace;
use App\LunchSetup;
use App\LunchTeaDate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LunchController extends Controller
{
    public function __construct()
    {
        $module_setup = get_module_setup();
        if (!isset($module_setup['午餐系統'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }

    public function index($lunch_order_id=null)
    {
        $lunch_order_array = [];
        $lunch_factory_array = [];
        $lunch_place_array = [];
        $lunch_setup = [];
        $has_order = "";
        $die_date = "";
        $month_die_date = "";
        $teacher_open="";
        $disable = "";

        $lunch_orders = LunchOrder::orderBy('name','DESC')
            ->get();
        foreach($lunch_orders as $lunch_order){
            $count = LunchTeaDate::where('lunch_order_id',$lunch_order->id)
                ->where('user_id',auth()->user()->id)
                ->count();
            $search_order = ($count)?" (已訂餐)":" (未訂餐)";
            $lunch_order_array[$lunch_order->id] = $lunch_order->name.$search_order;
        }

        $factories = LunchFactory::where('disable',null)
            ->get();
        foreach($factories as $factory){
            $lunch_factory_array[$factory->id] = $factory->name;
        }
        $lunch_place_array = LunchPlace::where('disable',null)
            ->pluck('name','id')
            ->toArray();
        $lunch_order = [];
        $eat_array=[];
        if($lunch_order_id){
            $lunch_order = LunchOrder::find($lunch_order_id);
            $has_order = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
                ->where('user_id',auth()->user()->id)
                ->count();
            $lunch_setup = LunchSetup::where('semester',$lunch_order->semester)->first();


            $eat_styles = explode(',',$lunch_setup->eat_styles);
            foreach($eat_styles as $eat_style){
                if($eat_style==1) $style="葷食合菜";
                if($eat_style==2) $style="素食合菜";
                if($eat_style==3) $style="葷食便當";
                if($eat_style==4) $style="素食便當";
                $eat_array[$eat_style] =$style;
            }


            $dt = Carbon::create(date('Y'), date('m'), date('d'), 0);
            $die_date = str_replace('-','',substr($dt->addDays($lunch_setup->die_line),0,10));
            $d = explode('-',$lunch_order->name);
            $dt2 = Carbon::create($d[0],$d[1],'01',0);
            $month_die_date = str_replace('-','',substr($dt2->subDays($lunch_setup->die_line),0,10));
            $teacher_open = $lunch_setup->teacher_open;
            $disable = $lunch_setup->disable;
        }

        //各學期訂餐統計
        $all_lunch_tea = [];
        $all_lunch_setups = LunchSetup::orderBy('id','DESC')->get();

        foreach($all_lunch_setups as $all_lunch_setup){
            $all_lunch_orders = LunchOrder::where('semester',$all_lunch_setup->semester)
            ->orderBy('id','DESC')
            ->get();
            foreach($all_lunch_orders as $all_lunch_order){
                $ds = LunchTeaDate::where('lunch_order_id',$all_lunch_order->id)
                    ->where('user_id',auth()->user()->id)
                    ->where('enable','eat')
                    ->get();

                $all_lunch_tea[$all_lunch_setup->semester][$all_lunch_order->name]['num'] = $ds->count();
                $all_lunch_tea[$all_lunch_setup->semester][$all_lunch_order->name]['t_money'] = $all_lunch_setup->teacher_money;

            }
        }

        $admin = check_power('午餐系統','A',auth()->user()->id);

        $data = [
            'admin'=>$admin,
            'lunch_setup'=>$lunch_setup,
            'lunch_order_id'=>$lunch_order_id,
            'lunch_order'=>$lunch_order,
            'lunch_order_array'=>$lunch_order_array,
            'lunch_factory_array'=>$lunch_factory_array,
            'lunch_place_array'=>$lunch_place_array,
            'eat_array'=>$eat_array,
            'has_order'=>$has_order,
            'die_date'=>$die_date,
            'month_die_date'=>$month_die_date,
            'teacher_open'=>$teacher_open,
            'disable'=>$disable,
            'all_lunch_tea'=>$all_lunch_tea,
        ];
        return view('lunches.index',$data);
    }

    public function store(Request $request)
    {
        $semester = $request->input('semester');
        $lunch_factory_id = $request->input('lunch_factory_id');

        if($request->input('select_place')=="place_select"){
            $lunch_place_id = $request->input('lunch_place_id');
        }elseif($request->input('select_place')=="place_class"){
            $lunch_place_id = "c".$request->input('class_no');
        }

        $eat_style = $request->input('eat_style');
        $lunch_order_id = $request->input('lunch_order_id');
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
                'user_id'=>auth()->user()->id,
                'lunch_place_id'=>$lunch_place_id,
                'lunch_factory_id'=>$lunch_factory_id,
                'eat_style'=>$eat_style,
                'created_at'=>now(),
                'updated_at'=>now(),
            ];
            array_push($all,$one);
        }

        $check_order = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
            ->where('user_id',auth()->user()->id)
            ->count();

        if($check_order ==0){
            LunchTeaDate::insert($all);
        }
        return redirect()->route('lunches.index');
    }

    public function update(Request $request)
    {
        $lunch_order_id = $request->input('lunch_order_id');
        $lunch_order = LunchOrder::find($lunch_order_id);
        $order_date = $request->input('order_date');
        $lunch_tea_dates = LunchTeaDate::where('lunch_order_id',$lunch_order_id)
            ->where('user_id',auth()->user()->id)
            ->orderBy('order_date')
            ->get();

        foreach($lunch_tea_dates as $lunch_tea_date){
            $new_enable = (isset($order_date[$lunch_tea_date->order_date]))?"eat":"not_eat";
            if($lunch_tea_date->enable != $new_enable){
                $att['enable'] = $new_enable;
                $lunch_tea_date->update($att);
            }
        }
        return redirect()->route('lunches.index',$lunch_order_id);
    }
}
