<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\ClassroomOrder;
use App\Http\Requests\BlogRequest;
use App\Blog;
use App\Setup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ClassroomOrderController extends Controller
{

    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['教室預約'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $classroom_admin = check_power('教室預約', 'A', auth()->user()->id);
        $classrooms = Classroom::where('disable', '=', null)->get();
        $data = [
            'classroom_admin' => $classroom_admin,
            'classrooms' => $classrooms,
        ];
        return view('classroom_orders.index', $data);
    }

    public function show(Classroom $classroom, $select_sunday = null)
    {
        $classroom_admin = check_power('教室預約', 'A', auth()->user()->id);

        $n = date('w', strtotime($select_sunday));
        $sunday = new Carbon($select_sunday);
        $sunday->subDays($n);

        $last_sunday = $sunday->subDays(7)->toDateString();
        $next_sunday = $sunday->addDays(14)->toDateString();

        $sunday->subDays(7);

        $week = [
            '0' => $sunday->toDateString(),
            '1' => $sunday->addDay()->toDateString(),
            '2' => $sunday->addDay()->toDateString(),
            '3' => $sunday->addDay()->toDateString(),
            '4' => $sunday->addDay()->toDateString(),
            '5' => $sunday->addDay()->toDateString(),
            '6' => $sunday->addDay()->toDateString(),
        ];

        $check_orders = ClassroomOrder::where('classroom_id', $classroom->id)
            ->get();
        $has_order = [];
        foreach ($check_orders as $check_order) {
            $has_order[$check_order->order_date][$check_order->section]['id'] = $check_order->user_id;
            $has_order[$check_order->order_date][$check_order->section]['user_name'] = $check_order->user->name;
        }

        $data = [
            'classroom_admin' => $classroom_admin,
            'classroom' => $classroom,
            'week' => $week,
            'has_order' => $has_order,
            'last_sunday' => $last_sunday,
            'next_sunday' => $next_sunday,
        ];
        return view('classroom_orders.show', $data);
    }

    public function block_show(Request $request)
    {
        $classrooms = Classroom::where('disable', '=', null)->get();

        $select_sunday = $request->input('select_sunday');
        $select_classroom = $request->input('select_classroom');
        $s_cht_week = config("chcschool.s_cht_week");
        $s_class_sections = config("chcschool.s_class_sections");

        $n = date('w', strtotime($select_sunday));
        $sunday = new Carbon($select_sunday);
        $sunday->subDays($n);

        $last_sunday = $sunday->subDays(7)->toDateString();
        $next_sunday = $sunday->addDays(14)->toDateString();

        $sunday->subDays(7);

        $week = [
            '0' => $sunday->toDateString(),
            '1' => $sunday->addDay()->toDateString(),
            '2' => $sunday->addDay()->toDateString(),
            '3' => $sunday->addDay()->toDateString(),
            '4' => $sunday->addDay()->toDateString(),
            '5' => $sunday->addDay()->toDateString(),
            '6' => $sunday->addDay()->toDateString(),
        ];

        $classroom_data = [];
        $has_order = [];

        foreach ($classrooms as $classroom) {
            $classroom_data[$classroom->id] = $classroom->name;
            $check_orders = ClassroomOrder::where('classroom_id', $classroom->id)
                ->get();
            foreach ($week as $k => $v) {
                foreach ($s_class_sections as $k1 => $v1) {
                    $has_order[$v][$k1][$classroom->id] = "";
                    if (strpos($classroom->close_sections, "'" . $k . "-" . $k1 . "'") !== false) {
                        $can_not_order[$v][$k1][$classroom->id] = 1;
                    } else {
                        $can_not_order[$v][$k1][$classroom->id] = "";
                    }
                }
            }
            foreach ($check_orders as $check_order) {
                $has_order[$check_order->order_date][$check_order->section][$classroom->id] = $check_order->user->name;
            }
        }

        $result['select_classroom'] = $select_classroom;
        $result['classroom_data'] = $classroom_data;
        $result['has_order'] = $has_order;
        $result['can_not_order'] = $can_not_order;
        $result['week'] = $week;
        $result['s_cht_week'] = $s_cht_week;
        $result['last_sunday'] = $last_sunday;
        $result['next_sunday'] = $next_sunday;
        $result['today'] = date('m-d');
        $result['today2'] = date('Y-m-d');
        $result['s_class_sections'] = $s_class_sections;

        echo json_encode($result);
        return;
    }

    public function select($classroom_id, $secton, $order_date)
    {
        $att['classroom_id'] = $classroom_id;
        $att['order_date'] = $order_date;
        $att['section'] = $secton;
        $att['user_id'] = auth()->user()->id;
        $check = ClassroomOrder::where('classroom_id', $classroom_id)
            ->where('section', $secton)
            ->where('order_date', $order_date)
            ->first();

        $check_date = str_replace('-', '', $order_date);
        if ($check_date < date('Ymd')) {
            return back();
        }
        if (empty($check)) {
            ClassroomOrder::create($att);
        } else {
            return redirect()->back();
        }

        return redirect()->route('classroom_orders.show', [$classroom_id, $order_date]);
    }

    public function destroy(Request $request)
    {
        ClassroomOrder::where('user_id', auth()->user()->id)
            ->where('classroom_id', $request->input('classroom_id'))
            ->where('order_date', $request->input('order_date'))
            ->where('section', $request->input('section'))
            ->delete();
        return redirect()->route('classroom_orders.show', [$request->input('classroom_id'), $request->input('order_date')]);
    }

    public function admin()
    {
        //不是管理者就離開
        $classroom_admin = check_power('教室預約', 'A', auth()->user()->id);
        if (!$classroom_admin) {
            return redirect()->back();
        }

        $classrooms = Classroom::all();
        $data = [
            'classrooms' => $classrooms,
        ];
        return view('classroom_orders.admin', $data);
    }

    public function admin_create()
    {
        //不是管理者就離開
        $classroom_admin = check_power('教室預約', 'A', auth()->user()->id);
        if (!$classroom_admin) {
            return redirect()->back();
        }

        $data = [];
        return view('classroom_orders.admin_create', $data);
    }

    public function admin_store(Request $request)
    {
        $att['name'] = $request->input('name');
        $att['disable'] = $request->input('disable');
        $close_section = $request->input('close_section');

        $att['close_sections'] = "";
        foreach ($close_section as $k => $v) {
            foreach ($v as $k1 => $v1) {
                $att['close_sections'] .= "'" . $k . "-" . $k1 . "',";
            }
        }
        $att['close_sections'] = substr($att['close_sections'], 0, -1);
        Classroom::create($att);
        return redirect()->route('classroom_orders.admin');
    }

    public function admin_edit(Classroom $classroom)
    {
        //不是管理者就離開
        $classroom_admin = check_power('教室預約', 'A', auth()->user()->id);
        if (!$classroom_admin) {
            return redirect()->back();
        }

        $data = [
            'classroom' => $classroom,
        ];
        return view('classroom_orders.admin_edit', $data);
    }

    public function admin_update(Request $request, Classroom $classroom)
    {
        $att['name'] = $request->input('name');
        $att['disable'] = $request->input('disable');
        $close_section = $request->input('close_section');

        $att['close_sections'] = "";
        foreach ($close_section as $k => $v) {
            foreach ($v as $k1 => $v1) {
                $att['close_sections'] .= "'" . $k . "-" . $k1 . "',";
            }
        }
        $att['close_sections'] = substr($att['close_sections'], 0, -1);
        $classroom->update($att);
        return redirect()->route('classroom_orders.admin');
    }

    public function admin_destroy(Classroom $classroom)
    {
        //不是管理者就離開
        $classroom_admin = check_power('教室預約', 'A', auth()->user()->id);
        if (!$classroom_admin) {
            return redirect()->back();
        }

        ClassroomOrder::where('classroom_id', $classroom->id)->delete();
        $classroom->delete();
        return redirect()->route('classroom_orders.admin');
    }
}
