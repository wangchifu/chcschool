<?php

namespace App\Http\Controllers;

use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchSetup;
use Illuminate\Http\Request;

class LunchOrderController extends Controller
{
    public function index()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);
        $lunch_orders = LunchOrder::orderBy('name','DESC')->paginate(6);
        $data = [
            'admin'=>$admin,
            'lunch_orders'=>$lunch_orders,
        ];
        return view('lunch_orders.index',$data);
    }

    public function create($semester)
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);
        //此學期的每一天
        $semester_dates = get_semester_dates($semester);


        $data = [
            'admin'=>$admin,
            'semester'=>$semester,
            'semester_dates'=>$semester_dates,
        ];
        return view('lunch_orders.create',$data);
    }

    public function store(Request $request)
    {
        $order_date = $request->input('order_date');
        $ps = $request->input('ps');
        $semester_dates = get_semester_dates($request->input('semester'));
        $lunch_setup = LunchSetup::where('semester',$request->input('semester'))
            ->first();


        $last_name = "";
        $all = [];
        foreach ($semester_dates as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $att['name'] = substr($v2, 0, 7);
                if ($att['name'] != $last_name) {
                    $att['semester'] = $request->input('semester');
                    $att['rece_name'] = $lunch_setup->all_rece_name;
                    $att['rece_date'] = $att['name'].'-28';
                    $att['rece_no'] = $lunch_setup->all_rece_no;
                    $att['rece_num'] = 1;

                    $lunch_order = LunchOrder::create($att);
                }
                $last_name = $att['name'];
                $att2['order_date'] = $v2;
                if (!empty($order_date[$v2])) {
                    $att2['enable'] = "1";
                } else {
                    $att2['enable'] = "0";
                }
                $att2['semester'] = $request->input('semester');
                $att2['lunch_order_id'] = $lunch_order->id;
                $att2['date_ps'] = $ps[$v2];
                $one = [
                    'order_date'=>$att2['order_date'],
                    'enable'=>$att2['enable'],
                    'semester'=>$att2['semester'],
                    'lunch_order_id'=>$att2['lunch_order_id'],
                    'date_ps'=>$att2['date_ps'],
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];
                array_push($all,$one);
            }
        }
        LunchOrderDate::insert($all);


        return redirect()->route('lunch_setups.index');
    }

    public function edit($semester)
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);

        //此學期的每一天
        $semester_dates = get_semester_dates($semester);
        $order_dates = LunchOrderDate::where('semester',$semester)->get();
        foreach($order_dates as $order_date){
            $order_date_data[$order_date->order_date] = $order_date->enable;
            $ps[$order_date->order_date] = $order_date->date_ps;
        }


        $data = [
            'admin'=>$admin,
            'semester'=>$semester,
            'semester_dates'=>$semester_dates,
            'order_date_data'=>$order_date_data,
            'ps'=>$ps,
        ];
        return view('lunch_orders.edit',$data);
    }

    public function update(Request $request)
    {
        //刪除舊的
        $semester = $request->input('semester');
        $ps = $request->input('ps');
        LunchOrder::where('semester',$semester)->delete();
        LunchOrderDate::where('semester',$semester)->delete();

        $order_date = $request->input('order_date');
        $semester_dates = get_semester_dates($request->input('semester'));

        $lunch_setup = LunchSetup::where('semester',$request->input('semester'))
            ->first();

        $last_name = "";
        $all = [];
        foreach ($semester_dates as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $att['name'] = substr($v2, 0, 7);
                if ($att['name'] != $last_name) {
                    $att['semester'] = $request->input('semester');
                    $att['rece_name'] = $lunch_setup->all_rece_name.'('.$att['name'].')';
                    $att['rece_date'] = $att['name'].'-28';
		    $att['rece_no'] = $lunch_setup->all_rece_no;
		    $att['rece_num'] = 1;

                    $lunch_order = LunchOrder::create($att);
                }
                $last_name = $att['name'];
                $att2['order_date'] = $v2;
                if (!empty($order_date[$v2])) {
                    $att2['enable'] = "1";
                } else {
                    $att2['enable'] = "0";
                }
                $att2['semester'] = $request->input('semester');
                $att2['lunch_order_id'] = $lunch_order->id;
                $att2['date_ps'] = $ps[$v2];
                $one = [
                    'order_date'=>$att2['order_date'],
                    'enable'=>$att2['enable'],
                    'semester'=>$att2['semester'],
                    'lunch_order_id'=>$att2['lunch_order_id'],
                    'date_ps'=>$att2['date_ps'],
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];
                array_push($all,$one);
            }
        }
        LunchOrderDate::insert($all);


        return redirect()->route('lunch_setups.index');
    }

    public function edit_order(Lunchorder $lunch_order)
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);
        $data = [
            'lunch_order'=>$lunch_order,
            'admin'=>$admin,
        ];
        return view('lunch_orders.edit_order',$data);
    }

    public function order_save(Request $request,Lunchorder $lunch_order)
    {
        $att['rece_name'] = $request->input('rece_name');
        $att['rece_date'] = $request->input('rece_date');
        $att['rece_num'] = $request->input('rece_num');
        $att['order_ps'] = $request->input('order_ps');
        $lunch_order->update($att);

        return redirect()->route('lunch_orders.index');
    }
}
