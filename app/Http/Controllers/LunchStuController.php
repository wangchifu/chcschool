<?php

namespace App\Http\Controllers;

use App\ClubStudent;
use App\LunchFactory;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchClassDate;
use App\LunchSetup;
use App\LunchStuDate;
use App\StudentClass;
use Illuminate\Http\Request;

class LunchStuController extends Controller
{
    public function index($lunch_order_id = null, $sample_date = null)
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        $lunch_orders = LunchOrder::orderBy('name', 'DESC')
            ->get();
        foreach ($lunch_orders as $lunch_order) {
            $check = LunchClassDate::where('lunch_order_id', $lunch_order->id)->count();
            $string = ($check > 0) ? "(已訂餐)" : "(未訂餐)";
            $lunch_order_array[$lunch_order->id] = $lunch_order->name . $string;
        }
        $lunch_order_id = (empty($lunch_order_id)) ? array_key_first($lunch_order_array) : $lunch_order_id;
        $lunch_order = LunchOrder::find($lunch_order_id);
        $semester = $lunch_order->semester;


        $factory_array = LunchFactory::where('disable', null)
            ->pluck('name', 'id')
            ->toArray();
        $lunch_class_data = [];
        $student_classes = [];
        if (!empty($semester)) {
            $lunch_class_dates = LunchClassDate::where('lunch_order_id', $lunch_order_id)
                ->orderBy('lunch_class_id')
                ->orderBy('order_date')
                ->get();
            foreach ($lunch_class_dates as $lunch_class_date) {
                $lunch_class_data[$lunch_class_date->lunch_class_id][$lunch_class_date->order_date][1] = $lunch_class_date->eat_style1;
                $lunch_class_data[$lunch_class_date->lunch_class_id][$lunch_class_date->order_date][4] = $lunch_class_date->eat_style4;
                $lunch_class_data[$lunch_class_date->lunch_class_id][$lunch_class_date->order_date]['factory'] = $factory_array[$lunch_class_date->lunch_factory_id];
            }

            $student_classes = StudentClass::where('semester', $semester)
                ->orderBy('student_year')
                ->orderBy('student_class')
                ->get();
        }

        $sample_data = [];
        if (!empty($sample_date)) {
            $sample_lunch_dates = LunchClassDate::where('order_date', $sample_date)
                ->orderBy('lunch_class_id')
                ->get();
            foreach ($sample_lunch_dates as $sample_lunch_date) {
                $sample_data[$sample_lunch_date->lunch_class_id][1] = $sample_lunch_date->eat_style1;
                $sample_data[$sample_lunch_date->lunch_class_id][4] = $sample_lunch_date->eat_style4;
            }
        }

        $data = [
            'admin' => $admin,
            'lunch_order_id' => $lunch_order_id,
            'semester' => $semester,
            'lunch_order_array' => $lunch_order_array,
            'lunch_order' => $lunch_order,
            'lunch_orders' => $lunch_orders,
            'student_classes' => $student_classes,
            'factory_array' => $factory_array,
            'lunch_class_dates' => $lunch_class_dates,
            'lunch_class_data' => $lunch_class_data,
            'sample_data' => $sample_data,
        ];
        return view('lunch_stus.index', $data);
    }

    public function store(Request $request, $lunch_order_id)
    {

        $eat_data1 = $request->input('eat_data1');
        $eat_data4 = $request->input('eat_data4');

        $lunch_factory_id = $request->input('lunch_factory_id');

        $lunch_order = LunchOrder::find($lunch_order_id);
        $semester = $lunch_order->semester;
        $lunch_order_dates = LunchOrderDate::where('lunch_order_id', $lunch_order_id)
            ->orderBy('order_date')
            ->get();
        foreach ($lunch_order_dates as $lunch_order_date) {
            $all = [];
            foreach ($eat_data1 as $k => $v) {
                $eat_style1 = ($lunch_order_date->enable == 1) ? $eat_data1[$k] : null;
                $eat_style4 = ($lunch_order_date->enable == 1) ? $eat_data4[$k] : null;
                $one = [
                    'lunch_class_id' => $k,
                    'order_date' => $lunch_order_date->order_date,
                    'semester' => $semester,
                    'lunch_order_id' => $lunch_order_date->lunch_order_id,
                    'lunch_factory_id' => $lunch_factory_id[$k],
                    'eat_style1' => $eat_style1,
                    'eat_style2' => null,
                    'eat_style3' => null,
                    'eat_style4' => $eat_style4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                array_push($all, $one);
            }
            LunchClassDate::insert($all);
        }


        return redirect()->route('lunch_stus.index', $lunch_order_id);
    }

    public function delete($lunch_order_id)
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);
        if ($admin) {
            LunchClassDate::where('lunch_order_id', $lunch_order_id)->delete();
        }
        return redirect()->route('lunch_stus.index', $lunch_order_id);
    }

    public function change_num(Request $request)
    {
        $request->validate([
            'lunch_class_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'eat_style1' => 'required',
        ]);
        $admin = check_power('午餐系統', 'A', auth()->user()->id);
        if ($admin) {
            $att['eat_style1'] = $request->input('eat_style1');
            $att['eat_style4'] = (empty($request->input('eat_style4'))) ? null : $request->input('eat_style4');
            LunchClassDate::where('lunch_class_id', $request->input('lunch_class_id'))
                ->where('order_date', '>=', $request->input('start_date'))
                ->where('order_date', '<=', $request->input('end_date'))
                ->where('eat_style1', '<>', null) //this date must provide lunch
                ->update($att);
        }

        return redirect()->back();
    }
    public function store_ps(Request $request, LunchOrder $lunch_order)
    {
        $att['order_ps_ps'] = $request->input('order_ps_ps');
        //dd($lunch_order);
        $lunch_order->update($att);
        return redirect()->back();
    }
}
