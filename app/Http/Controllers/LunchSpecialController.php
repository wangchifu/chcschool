<?php

namespace App\Http\Controllers;

use App\LunchFactory;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchPlace;
use App\LunchSetup;
use App\LunchTeaDate;
use App\User;
use Illuminate\Http\Request;

class LunchSpecialController extends Controller
{
    public function index()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);
        $data = [
            'admin' => $admin,
        ];
        return view('lunch_specials.index', $data);
    }

    public function one_day()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);
        $data = [
            'admin' => $admin,
        ];
        return view('lunch_specials.one_day', $data);
    }

    public function one_day_store(Request $request)
    {
        if ($request->input('action') == "not_eat") {
            $lunch_order_date = LunchOrderDate::where('order_date', $request->input('order_date'))
                ->first();
            $att['enable'] = 0;
            if ($lunch_order_date) {
                $lunch_order_date->update($att);
            } else {
                return back()->withErrors(['error' => ['查無該日期資料！']]);
            }
            $att2['enable'] = "not_eat";
            LunchTeaDate::where('order_date', $request->input('order_date'))
                ->update($att2);
        } elseif ($request->input('action') == "eat") {
            $lunch_order_date = LunchOrderDate::where('order_date', $request->input('order_date'))
                ->first();
            $att['enable'] = 1;
            if ($lunch_order_date) {
                $lunch_order_date->update($att);
            } else {
                return back()->withErrors(['error' => ['查無該日期資料！']]);
            }
        }

        return redirect()->route('lunch_specials.index');
    }

    public function late_teacher()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        $user_array = User::where('disable', null)
            ->where('username', '<>', 'admin')
            ->orderBy('order_by')
            ->pluck('name', 'id')
            ->toArray();

        $lunch_order_array = LunchOrder::orderBy('name', 'DESC')
            ->pluck('name', 'id')
            ->toArray();
        $data = [
            'admin' => $admin,
            'user_array' => $user_array,
            'lunch_order_array' => $lunch_order_array,
        ];
        return view('lunch_specials.late_teacher', $data);
    }

    public function late_teacher_show(Request $request)
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        //是否已經訂過
        $count = LunchTeaDate::where('user_id', $request->input('user_id'))
            ->where('lunch_order_id', $request->input('lunch_order_id'))
            ->count();
        $lunch_order = LunchOrder::find($request->input('lunch_order_id'));
        if ($count) {
            return back()->withErrors(['error' => ['該員 ' . $lunch_order->name . ' 已訂過餐！']]);
        }



        $lunch_factory_array = [];
        $lunch_place_array = [];

        $lunch_order = LunchOrder::find($request->input('lunch_order_id'));
        $user = User::find($request->input('user_id'));

        $factories = LunchFactory::where('disable', null)
            ->get();
        foreach ($factories as $factory) {
            $lunch_factory_array[$factory->id] = $factory->name;
        }
        $lunch_place_array = LunchPlace::where('disable', null)
            ->pluck('name', 'id')
            ->toArray();

        $lunch_setup = LunchSetup::where('semester', $lunch_order->semester)->first();


        $eat_styles = explode(',', $lunch_setup->eat_styles);
        foreach ($eat_styles as $eat_style) {
            if ($eat_style == 1) $style = "葷食合菜";
            if ($eat_style == 2) $style = "素食合菜";
            if ($eat_style == 3) $style = "葷食便當";
            if ($eat_style == 4) $style = "素食便當";
            $eat_array[$eat_style] = $style;
        }

        $eat_styles = $eat_array;

        $data = [
            'admin' => $admin,
            'user' => $user,
            'lunch_order' => $lunch_order,
            'lunch_factory_array' => $lunch_factory_array,
            'lunch_place_array' => $lunch_place_array,
            'eat_styles' => $eat_styles,
        ];

        return view('lunch_specials.late_teacher_show', $data);
    }

    public function late_teacher_store(Request $request)
    {
        $semester = $request->input('semester');
        $lunch_factory_id = $request->input('lunch_factory_id');
        if ($request->input('select_place') == "place_select") {
            $lunch_place_id = $request->input('lunch_place_id');
        } elseif ($request->input('select_place') == "place_class") {
            $lunch_place_id = "c" . $request->input('class_no');
        }
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
                'order_date' => $lunch_order_date->order_date,
                'enable' => $enable,
                'semester' => $semester,
                'lunch_order_id' => $lunch_order_id,
                'user_id' => $user_id,
                'lunch_place_id' => $lunch_place_id,
                'lunch_factory_id' => $lunch_factory_id,
                'eat_style' => $eat_style,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            array_push($all, $one);
        }

        LunchTeaDate::insert($all);

        return redirect()->route('lunch_specials.index');
    }

    public function teacher_change_month()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        $user_array = User::where('disable', null)
            //->where('username', '<>', 'admin')
            ->orderBy('order_by')
            ->pluck('name', 'id')
            ->toArray();

        $lunch_order_array = LunchOrder::orderBy('name', 'DESC')
            ->pluck('name', 'id')
            ->toArray();
        $data = [
            'admin' => $admin,
            'user_array' => $user_array,
            'lunch_order_array' => $lunch_order_array,
        ];
        return view('lunch_specials.teacher_change_month', $data);
    }

    public function teacher_change_month_show(Request $request)
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        //是否已經訂過
        $count = LunchTeaDate::where('user_id', $request->input('user_id'))
            ->where('lunch_order_id', $request->input('lunch_order_id'))
            ->count();
        $lunch_order = LunchOrder::find($request->input('lunch_order_id'));
        if (!$count) {
            return back()->withErrors(['error' => ['該員 ' . $lunch_order->name . ' 尚末訂過餐！']]);
        }



        $lunch_factory_array = [];
        $lunch_place_array = [];

        $lunch_order = LunchOrder::find($request->input('lunch_order_id'));
        $user = User::find($request->input('user_id'));

        $factories = LunchFactory::where('disable', null)
            ->get();
        foreach ($factories as $factory) {
            $lunch_factory_array[$factory->id] = $factory->name;
        }
        $lunch_place_array = LunchPlace::where('disable', null)
            ->pluck('name', 'id')
            ->toArray();


        $lunch_setup = LunchSetup::where('semester', $lunch_order->semester)->first();

        $eat_styless = explode(',', $lunch_setup->eat_styles);
        foreach ($eat_styless as $eat_style) {
            if ($eat_style == 1) $style = "葷食合菜";
            if ($eat_style == 2) $style = "素食合菜";
            if ($eat_style == 3) $style = "葷食便當";
            if ($eat_style == 4) $style = "素食便當";
            $eat_styles[$eat_style] = $style;
        }


        $lunch_tea_dates = LunchTeaDate::where('lunch_order_id', $lunch_order->id)
            ->where('user_id', $request->input('user_id'))
            ->get();
        foreach ($lunch_tea_dates as $lunch_tea_date) {
            $tea_data[$lunch_tea_date->order_date] = $lunch_tea_date->enable;
        }
        $days = \App\LunchTeaDate::where('lunch_order_id', $lunch_order->id)
            ->where('user_id', $user->id)
            ->where('enable', 'eat')
            ->count();

        $data = [
            'admin' => $admin,
            'user' => $user,
            'lunch_order' => $lunch_order,
            'lunch_factory_array' => $lunch_factory_array,
            'lunch_place_array' => $lunch_place_array,
            'eat_styles' => $eat_styles,
            'tea_data' => $tea_data,
            'days' => $days,
        ];

        return view('lunch_specials.teacher_change_month_show', $data);
    }
    public function teacher_update_month(Request $request)
    {
        $semester = $request->input('semester');
        $lunch_factory_id = $request->input('lunch_factory_id');

        if ($request->input('select_place') == "place_select") {
            $lunch_place_id = $request->input('lunch_place_id');
        } elseif ($request->input('select_place') == "place_class") {
            $lunch_place_id = "c" . $request->input('class_no');
        }
        $eat_style = $request->input('eat_style');


        $lunch_order_id = $request->input('lunch_order_id');
        $user_id = $request->input('user_id');

        $order_date = $request->input('order_date');

        $tea_dates = LunchTeaDate::where('lunch_order_id', $lunch_order_id)
            ->where('user_id', $user_id)
            ->get();
        foreach ($tea_dates as $tea_date) {
            if (isset($order_date[$tea_date->order_date])) {
                $enable = "eat";
            } else {
                $enable = "not_eat";
            }
            $att['enable'] = $enable;
            $att['lunch_place_id'] = $lunch_place_id;
            $att['lunch_factory_id'] = $lunch_factory_id;
            $att['eat_style'] = $eat_style;
            $tea_date->update($att);
        }

        return redirect()->route('lunch_specials.index');
    }


    /**
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
     **/




    public function teacher_change()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);
        $user_array = User::where('disable', null)
            ->where('username', '<>', 'admin')
            ->orderBy('order_by')
            ->pluck('name', 'id')
            ->toArray();
        $data = [
            'admin' => $admin,
            'user_array' => $user_array,
        ];
        return view('lunch_specials.teacher_change', $data);
    }

    public function teacher_change_store(Request $request)
    {
        $lunch_tea_date = LunchTeaDate::where('order_date', $request->input('order_date'))
            ->where('user_id', $request->input('user_id'))
            ->first();
        if ($lunch_tea_date) {
            $att['enable'] = $request->input('action');
            $lunch_tea_date->update($att);
        } else {
            return back()->withErrors(['error' => ['查無該日期資料！']]);
        }

        return redirect()->route('lunch_specials.index');
    }

    public function bad_factory()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);
        $factories = LunchFactory::where('disable', null)->pluck('name', 'id')->toArray();
        $data = [
            'admin' => $admin,
            'factories' => $factories,
        ];
        return view('lunch_specials.bad_factory', $data);
    }

    public function bad_factory2(Request $request)
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);
        $factories = LunchFactory::where('disable', null)->pluck('name', 'id')->toArray();
        foreach ($factories as $k => $v) {
            if ($k != $request->input('bad_factory_id')) {
                $factories2[$k] = $v;
            } else {
                $bad_factory = $v;
            }
        }
        $bad_factory_id = $request->input('bad_factory_id');
        $order_date1 = $request->input('order_date1');
        $order_date2 = $request->input('order_date2');
        $teachers = [];
        $teas = [];

        $tea_orders = LunchTeaDate::where('lunch_factory_id', $bad_factory_id)
            ->where('order_date', '>=', $order_date1)
            ->where('order_date', '<=', $order_date2)
            ->get();
        foreach ($tea_orders as $tea_order) {
            $teachers[$tea_order->user_id] = 1;
        }
        foreach ($teachers as $k => $v) {
            $user = User::find($k);
            $teas[$k] = $user->name;
        }

        $data = [
            'admin' => $admin,
            'factories' => $factories2,
            'bad_factory_id' => $bad_factory_id,
            'bad_factory' => $bad_factory,
            'order_date1' => $order_date1,
            'order_date2' => $order_date2,
            'teas' => $teas
        ];
        return view('lunch_specials.bad_factory2', $data);
    }

    public function bad_factory3(Request $request)
    {
        $bad_factory_id = $request->input('bad_factory_id');
        $order_date1 = $request->input('order_date1');
        $order_date2 = $request->input('order_date2');
        $good_factory_id = $request->input('good_factory_id');
        $teas = $request->input('teas');

        $att['lunch_factory_id'] = $good_factory_id;
        foreach ($teas as $k => $v) {
            $tea_orders = LunchTeaDate::where('lunch_factory_id', $bad_factory_id)
                ->where('order_date', '>=', $order_date1)
                ->where('order_date', '<=', $order_date2)
                ->where('user_id', $v)
                ->update($att);
        }

        return redirect()->route('lunch_specials.index');
    }

    public function add7(Request $request)
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        $semester = $request->input('semester');
        $has7 = null;
        if ($semester) {
            $this_year = substr($semester, 0, 3) + 1911;
            if (substr($semester, -1, 1) == "2") {
                $this_year++;
            }
            $semester_dates = get_month_date($this_year . "-07");

            $has7 = LunchOrder::where('name', $this_year . "-07")->first();
        } else {
            $semester_dates = [];
            $this_year = "";
        }

        $data = [
            'admin' => $admin,
            'has7' => $has7,
            'this_year' => $this_year,
            'semester' => $semester,
            'semester_dates' => $semester_dates,
        ];
        return view('lunch_specials.add7', $data);
    }

    public function store7(Request $request)
    {
        $order_date = $request->input('order_date');
        $ps = $request->input('ps');

        $lunch_setup = LunchSetup::where('semester', $request->input('semester'))
            ->first();

        $att['name'] = $request->input('name');
        $att['semester'] = $request->input('semester');
        $att['rece_name'] = $lunch_setup->all_rece_name;
        $att['rece_date'] = $att['name'] . '-28';
        $att['rece_no'] = $lunch_setup->all_rece_no;
        $att['rece_num'] = 1;
        $lunch_order = LunchOrder::create($att);

        $semester_dates = get_month_date($att['name']);
        foreach ($semester_dates as $k => $v) {
            if (isset($order_date[$v])) {
                $att2['enable'] = "1";
            } else {
                $att2['enable'] = "0";
            }
            $att2['order_date'] = $v;
            $att2['semester'] = $request->input('semester');
            $att2['lunch_order_id'] = $lunch_order->id;
            if (isset($ps[$v])) {
                $att2['date_ps'] = $ps[$v];
            } else {
                $att2['date_ps'] = null;
            }

            LunchOrderDate::create($att2);
        }

        return redirect()->route('lunch_specials.index');
    }
}
