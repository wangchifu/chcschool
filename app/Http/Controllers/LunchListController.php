<?php

namespace App\Http\Controllers;

use App\LunchClassDate;
use App\LunchFactory;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchSetup;
use App\LunchTeaDate;
use App\StudentClass;
use Illuminate\Http\Request;
use PHPExcel_IOFactory;
use PHPExcel;

class LunchListController extends Controller
{
    public function index()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);
        $data = [
            'admin' => $admin,
        ];
        return view('lunch_lists.index', $data);
    }

    public function every_day($lunch_order_id = null)
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        $lunch_order_array = LunchOrder::orderBy('name', 'DESC')
            ->pluck('name', 'id')
            ->toArray();

        $date_array = [];
        $user_data = [];
        $factory_data = [];
        $place_data = [];
        $eat_data = [];
        $days_data = [];
        $money_data = [];
        if ($lunch_order_id) {
            $lunch_order = LunchOrder::find($lunch_order_id);
            $lunch_setup = LunchSetup::where('semester', $lunch_order->semester)->first();
            $date_array = $this->get_order_date($lunch_order_id);

            $tea_dates = LunchTeaDate::where('lunch_order_id', $lunch_order_id)
                ->where('enable', 'eat')
                ->orderBy('lunch_place_id')
                ->orderBy('order_date')
                ->get();
            foreach ($tea_dates as $tea_date) {
                $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;
                $factory_data[$tea_date->user->name][$tea_date->order_date]['name'] = $tea_date->lunch_factory->name;
                $factory_data[$tea_date->user->name][$tea_date->order_date]['id'] = $tea_date->lunch_factory->id;
                if (substr($tea_date->lunch_place_id, 0, 1) == "c") {
                    $place_data[$tea_date->user->name] = substr($tea_date->lunch_place_id, 1, 4) . "教室";
                } else {
                    $place_data[$tea_date->user->name] = $tea_date->lunch_place->name;
                }
                $eat_data[$tea_date->user->name] = $tea_date->eat_style;
                if ($tea_date->enable == "eat") {
                    if (!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name] = 0;
                    $days_data[$tea_date->user->name]++;
                    if (!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name] = 0;
                    $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
                }
            }
        }

        $data = [
            'lunch_order_id' => $lunch_order_id,
            'admin' => $admin,
            'lunch_order_array' => $lunch_order_array,
            'date_array' => $date_array,
            'user_data' => $user_data,
            'factory_data' => $factory_data,
            'place_data' => $place_data,
            'eat_data' => $eat_data,
            'days_data' => $days_data,
            'money_data' => $money_data,
        ];
        return view('lunch_lists.every_day', $data);
    }

    public function teacher_money_print($lunch_order_id)
    {
        $lunch_order = LunchOrder::find($lunch_order_id);

        $order_datas = LunchTeaDate::where('lunch_order_id', $lunch_order_id)
            ->where('enable', 'eat')
            ->orderBy('lunch_place_id')
            ->get();

        $lunch_setup = LunchSetup::where('semester', $lunch_order->semester)->first();

        $user_datas = [];
        $factory_money = [];
        foreach ($order_datas as $order_data) {
            if ($order_data->enable == "eat") {
                if (!isset($user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)] = null;
                $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]++;
                $factory_money[$order_data->user->name] = $lunch_setup->teacher_money;
            }
        }

        $data = [
            'lunch_setup' => $lunch_setup,
            'lunch_order' => $lunch_order,
            'user_datas' => $user_datas,
            'factory_money' => $factory_money,
        ];
        return view('lunch_lists.teacher_money_print', $data);
    }

    public function every_day_download($lunch_order_id)
    {
        $lunch_order = LunchOrder::find($lunch_order_id);

        $order_datas = LunchTeaDate::where('lunch_order_id', $lunch_order_id)
            ->where('enable', 'eat')
            ->orderBy('lunch_place_id')
            ->get();

        $lunch_setup = LunchSetup::where('semester', $lunch_order->semester)->first();

        $user_datas = [];
        $u_id = [];
        $factory_money = [];
        foreach ($order_datas as $order_data) {
            if ($order_data->enable == "eat") {
                if (!isset($user_datas[$order_data->user->id][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->id][substr($order_data->order_date, 0, 7)] = null;
                $user_datas[$order_data->user->id][substr($order_data->order_date, 0, 7)]++;
                $u_id[$order_data->user->id] = $order_data->user->name;
                $factory_money[$order_data->user->id] = $lunch_setup->teacher_money;
            }
        }
        // $excel_data = [];
        // $i = 0;

        /** 
        foreach ($user_datas as $k1 => $v1) {
            $count = 0;
            foreach ($v1 as $k2 => $v2) {
                $money = $factory_money[$k1] * $v2;
                $count += $v2;
            }
            $total_money = $factory_money[$k1] * $count;
            $total_money2 = round($total_money);

            $excel_data[$i]['學號'] =  "999" . sprintf("%03s", $k1);
            $excel_data[$i]['姓名'] = $u_id[$k1];
            $excel_data[$i]['生日'] = "";
            $excel_data[$i]['座號'] = "";
            $excel_data[$i]['減免'] = "";
            $excel_data[$i]['年級'] = "";
            $excel_data[$i]['班別'] = "";
            $excel_data[$i]['身分證字號'] = "";
            $excel_data[$i]['午餐費'] = (int)$total_money2;
            $i++;
        }
        $list = collect($excel_data);
        return (new FastExcel($list))->download($lunch_order->name . '午餐收費匯入單.xlsx');
         **/


        $objExcel = new PHPExcel();
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objActSheet = $objExcel->getActiveSheet(0);
        $objActSheet->setTitle('午餐收費名單'); //设置excel的标题
        $objActSheet->setCellValue('A1', '學號');
        $objActSheet->setCellValue('B1', '姓名');
        $objActSheet->setCellValue('C1', '生日');
        $objActSheet->setCellValue('D1', '座號');
        $objActSheet->setCellValue('E1', '減免');
        $objActSheet->setCellValue('F1', '年級');
        $objActSheet->setCellValue('G1', '班別');
        $objActSheet->setCellValue('H1', '身分證字號');
        $objActSheet->setCellValue('I1', '午餐費');

        $baseRow = 2; //数据从N-1行开始往下输出 这里是避免头信息被覆盖
        foreach ($user_datas as $k1 => $v1) {
            $count = 0;
            foreach ($v1 as $k2 => $v2) {
                $money = $factory_money[$k1] * $v2;
                $count += $v2;
            }
            $total_money = $factory_money[$k1] * $count;
            $total_money2 = round($total_money);
            $m = (int)$total_money2;

            $objExcel->getActiveSheet()->setCellValue('A' . $baseRow, "9" . sprintf("%05s", $k1));
            $u_id[$k1] = mb_convert_encoding(mb_convert_encoding($u_id[$k1], 'big5', 'utf-8'), 'utf-8', 'big5');
            $objExcel->getActiveSheet()->setCellValue('B' . $baseRow, $u_id[$k1]);
            $objExcel->getActiveSheet()->setCellValue('C' . $baseRow, '');
            $objExcel->getActiveSheet()->setCellValue('D' . $baseRow, '');
            $objExcel->getActiveSheet()->setCellValue('E' . $baseRow, '');
            $objExcel->getActiveSheet()->setCellValue('F' . $baseRow, '');
            $objExcel->getActiveSheet()->setCellValue('G' . $baseRow, '');
            $objExcel->getActiveSheet()->setCellValue('H' . $baseRow, '');
            $objExcel->getActiveSheet()->setCellValue('I' . $baseRow, (string)$m);
            $baseRow++;
        }

        $objExcel->setActiveSheetIndex(0);
        //4、输出
        $objExcel->setActiveSheetIndex();
        header('Content-Type: applicationnd.ms-excel');
        $time = date('Y-m-d');
        header("Content-Disposition: attachment;filename={$lunch_order->name}午餐收費匯入單.xls");
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }


    public function all_semester()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        $lunch_setup_array = LunchSetup::orderBy('semester', 'DESC')
            ->pluck('semester', 'id')
            ->toArray();


        $data = [
            'lunch_setup_array' => $lunch_setup_array,
            'admin' => $admin,

        ];
        return view('lunch_lists.all_semester', $data);
    }

    public function semester_print(Request $request)
    {
        if ($request->input('submit') == "印出教師全學期收費通知") {
            $lunch_setup = LunchSetup::find($request->input('lunch_setup_id'));

            $order_datas = LunchTeaDate::where('semester', $lunch_setup->semester)
                ->orderBy('lunch_order_id')
                ->orderBy('lunch_place_id')
                ->get();

            $user_datas = [];
            $factory_money = [];
            foreach ($order_datas as $order_data) {
                if ($order_data->enable == "eat") {
                    if (!isset($user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)] = null;
                    $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]++;
                    $factory_money[$order_data->user->name][substr($order_data->order_date, 0, 7)] = $lunch_setup->teacher_money;
                }
            }


            $data = [
                'lunch_setup' => $lunch_setup,
                'user_datas' => $user_datas,
                'factory_money' => $factory_money,
                'lunch_setup' => $lunch_setup,
            ];
            return view('lunch_lists.semester_call_money', $data);
        }

        if ($request->input('submit') == "印出教師全學期收據") {
            $lunch_setup = LunchSetup::find($request->input('lunch_setup_id'));

            $order_datas = LunchTeaDate::where('semester', $lunch_setup->semester)
                ->orderBy('lunch_order_id')
                ->orderBy('lunch_place_id')
                ->get();

            $lunch_orders = LunchOrder::where('semester', $lunch_setup->semester)
                ->get();

            $user_datas = [];
            $factory_money = [];
            foreach ($order_datas as $order_data) {
                if ($order_data->enable == "eat") {
                    if (!isset($user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)] = null;
                    $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]++;
                    $factory_money = $lunch_setup->teacher_money;
                }
            }

            $data = [
                'lunch_setup' => $lunch_setup,
                'user_datas' => $user_datas,
                'factory_money' => $factory_money,
                'lunch_orders' => $lunch_orders,
            ];
            return view('lunch_lists.semester_print', $data);
        }

        if ($request->input('submit') == "印出廠商全學期收入") {
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
            $tea_dates = LunchTeaDate::where('semester', $lunch_setup->semester)
                ->where('enable', 'eat')
                ->orderBy('lunch_place_id')
                ->get();
            foreach ($tea_dates as $tea_date) {
                if (!isset($order_data[$tea_date->lunch_factory->name][$tea_date->user->name])) $order_data[$tea_date->lunch_factory->name][$tea_date->user->name] = 0;
                $order_data[$tea_date->lunch_factory->name][$tea_date->user->name]++;
            }

            $data = [
                'order_data' => $order_data,
                'lunch_setup' => $lunch_setup,
            ];

            return view('lunch_lists.semester_factory', $data);
        }
        if ($request->input('submit') == "下載全學期「彰化智慧校園」收費模組匯入單") {
            $lunch_setup = LunchSetup::find($request->input('lunch_setup_id'));

            $order_datas = LunchTeaDate::where('semester', $lunch_setup->semester)
                ->orderBy('lunch_order_id')
                ->orderBy('lunch_place_id')
                ->get();

            $user_datas = [];
            $u_id = [];
            $factory_money = [];
            foreach ($order_datas as $order_data) {
                if ($order_data->enable == "eat") {
                    if (!isset($user_datas[$order_data->user->id][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->id][substr($order_data->order_date, 0, 7)] = null;
                    $user_datas[$order_data->user->id][substr($order_data->order_date, 0, 7)]++;
                    $u_id[$order_data->user->id] = $order_data->user->name;
                    $factory_money[$order_data->user->id] = $lunch_setup->teacher_money;
                }
            }

            $objExcel = new PHPExcel();
            $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
            $objActSheet = $objExcel->getActiveSheet(0);
            $objActSheet->setTitle('午餐收費名單'); //设置excel的标题
            $objActSheet->setCellValue('A1', '學號');
            $objActSheet->setCellValue('B1', '姓名');
            $objActSheet->setCellValue('C1', '生日');
            $objActSheet->setCellValue('D1', '座號');
            $objActSheet->setCellValue('E1', '減免');
            $objActSheet->setCellValue('F1', '年級');
            $objActSheet->setCellValue('G1', '班別');
            $objActSheet->setCellValue('H1', '身分證字號');
            $objActSheet->setCellValue('I1', '午餐費');

            $baseRow = 2; //数据从N-1行开始往下输出 这里是避免头信息被覆盖
            foreach ($user_datas as $k1 => $v1) {
                $count = 0;
                foreach ($v1 as $k2 => $v2) {
                    $money = $factory_money[$k1] * $v2;
                    $count += $v2;
                }
                $total_money = $factory_money[$k1] * $count;
                $total_money2 = round($total_money);
                $m = (int)$total_money2;

                $objExcel->getActiveSheet()->setCellValue('A' . $baseRow, "9" . sprintf("%05s", $k1));
                $u_id[$k1] = mb_convert_encoding(mb_convert_encoding($u_id[$k1], 'big5', 'utf-8'), 'utf-8', 'big5');
                $objExcel->getActiveSheet()->setCellValue('B' . $baseRow, $u_id[$k1]);
                $objExcel->getActiveSheet()->setCellValue('C' . $baseRow, '');
                $objExcel->getActiveSheet()->setCellValue('D' . $baseRow, '');
                $objExcel->getActiveSheet()->setCellValue('E' . $baseRow, '');
                $objExcel->getActiveSheet()->setCellValue('F' . $baseRow, '');
                $objExcel->getActiveSheet()->setCellValue('G' . $baseRow, '');
                $objExcel->getActiveSheet()->setCellValue('H' . $baseRow, '');
                $objExcel->getActiveSheet()->setCellValue('I' . $baseRow, (string)$m);
                $baseRow++;
            }

            $objExcel->setActiveSheetIndex(0);
            //4、输出
            $objExcel->setActiveSheetIndex();
            header('Content-Type: applicationnd.ms-excel');
            $time = date('Y-m-d');
            header("Content-Disposition: attachment;filename={$lunch_setup->semester}學期午餐收費匯入單.xls");
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        }
    }

    public function call_money($lunch_order_id)
    {
        $user_data = [];
        $factory_data = [];
        $days_data = [];
        $money_data = [];

        $lunch_order = LunchOrder::find($lunch_order_id);
        $lunch_setup = LunchSetup::where('semester', $lunch_order->semester)->first();
        $date_array = $this->get_order_date($lunch_order_id);

        $tea_dates = LunchTeaDate::where('lunch_order_id', $lunch_order_id)
            ->where('enable', 'eat')
            ->orderBy('order_date')
            ->orderBy('lunch_place_id')
            ->get();
        foreach ($tea_dates as $tea_date) {
            $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;

            if (substr($tea_date->lunch_place_id, 0, 1) == "c") {
                $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place_id . " 教室";
            } else {
                $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place->name;
            }

            $user_data[$tea_date->user->name][$tea_date->order_date]['eat_style'] = $tea_date->eat_style;
            $factory_data[$tea_date->user->name] = $tea_date->lunch_factory->name;
            if ($tea_date->enable == "eat") {
                if (!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name] = 0;
                $days_data[$tea_date->user->name]++;
                if (!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name] = 0;
                $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
            }
        }

        $teacher_money = $lunch_setup->teacher_money;
        $data = [
            'lunch_order' => $lunch_order,
            'lunch_order_id' => $lunch_order_id,
            'date_array' => $date_array,
            'user_data' => $user_data,
            'factory_data' => $factory_data,
            'days_data' => $days_data,
            'money_data' => $money_data,
            'teacher_money' => $teacher_money,
        ];
        return view('lunch_lists.call_money', $data);
    }


    public function get_money($lunch_order_id)
    {
        $user_data = [];
        $factory_data = [];
        $days_data = [];
        $money_data = [];

        $lunch_order = LunchOrder::find($lunch_order_id);
        $lunch_setup = LunchSetup::where('semester', $lunch_order->semester)->first();
        $date_array = $this->get_order_date($lunch_order_id);

        $tea_dates = LunchTeaDate::where('lunch_order_id', $lunch_order_id)
            ->where('enable', 'eat')
            ->orderBy('order_date')
            ->orderBy('lunch_place_id')
            ->get();
        foreach ($tea_dates as $tea_date) {
            $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;

            if (substr($tea_date->lunch_place_id, 0, 1) == "c") {
                $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place_id . " 教室";
            } else {
                $user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place->name;
            }

            $user_data[$tea_date->user->name][$tea_date->order_date]['eat_style'] = $tea_date->eat_style;
            $factory_data[$tea_date->user->name] = $tea_date->lunch_factory->name;
            if ($tea_date->enable == "eat") {
                if (!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name] = 0;
                $days_data[$tea_date->user->name]++;
                if (!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name] = 0;
                $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
            }
        }
        $data = [
            'lunch_order' => $lunch_order,
            'lunch_order_id' => $lunch_order_id,
            'date_array' => $date_array,
            'user_data' => $user_data,
            'factory_data' => $factory_data,
            'days_data' => $days_data,
            'money_data' => $money_data,
        ];
        return view('lunch_lists.get_money', $data);
    }

    public function more_list_factory($lunch_order_id, $factory_id)
    {
        $factory = LunchFactory::find($factory_id);
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        $lunch_order = LunchOrder::find($lunch_order_id);
        $lunch_setup = LunchSetup::where('semester', $lunch_order->semester)->first();

        $lunch_order_array = LunchOrder::orderBy('name', 'DESC')
            ->pluck('name', 'id')
            ->toArray();

        $date_array = [];
        $user_data = [];
        //$factory_data=[];
        $place_data = [];
        $eat_data = [];
        $days_data = [];
        $money_data = [];

        $date_array = $this->get_order_date($lunch_order_id);

        $tea_dates = LunchTeaDate::where('lunch_order_id', $lunch_order_id)
            ->where('lunch_factory_id', $factory_id)
            ->where('enable', 'eat')
            ->orderBy('order_date')
            ->get();
        foreach ($tea_dates as $tea_date) {
            $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;
            //$user_data[$tea_date->user->name][$tea_date->order_date]['place'] = $tea_date->lunch_place->name;
            //$user_data[$tea_date->user->name][$tea_date->order_date]['eat_style'] = $tea_date->eat_style;
            //$factory_data[$tea_date->user->name] = $tea_date->lunch_factory->name;
            if (substr($tea_date->lunch_place_id, 0, 1) == "c") {
                $place_data[$tea_date->user->name] = substr($tea_date->lunch_place_id, 1, 3) . "教室";
            } else {
                $place_data[$tea_date->user->name] = $tea_date->lunch_place->name;
            }
            $eat_data[$tea_date->user->name] = $tea_date->eat_style;
            if ($tea_date->enable == "eat") {
                if (!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name] = 0;
                $days_data[$tea_date->user->name]++;
                if (!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name] = 0;
                $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
            }
        }


        $data = [
            'lunch_order_id' => $lunch_order_id,
            'admin' => $admin,
            'lunch_order_array' => $lunch_order_array,
            'date_array' => $date_array,
            'user_data' => $user_data,
            'factory' => $factory,
            'place_data' => $place_data,
            'eat_data' => $eat_data,
            'days_data' => $days_data,
            'money_data' => $money_data,
        ];
        return view('lunch_lists.more_list_factory', $data);
    }


    public function factory(Request $request, $lunch_order_id = null)
    {
        $data = [];

        if ($request->input('username')) {
            $factory = LunchFactory::where('fid', $request->input('username'))
                ->first();

            /**
            if($request->input('chaptcha') != session('chaptcha')){
                return back()->withErrors(['gsuite_error'=>['驗證碼錯誤！']]);
            }
             * */
            if (empty($factory)) {
                if (!$factory) return back()->withErrors(['error' => ['查無此帳號！']]);
            } else {
                if ($request->input('password') != $factory->fpwd) {
                    return back()->withErrors(['gsuite_error' => ['密碼錯誤！']]);
                } else {
                    session(['factory' => $factory->fid]);
                };
            }
        }

        if (session('factory')) {
            $factory = LunchFactory::where('fid', session('factory'))->first();
            $lunch_order_array = LunchOrder::orderBy('name', 'DESC')
                ->pluck('name', 'id')
                ->toArray();

            $data = [
                'factory' => $factory,
                'lunch_order_id' => $lunch_order_id,
                'lunch_order_array' => $lunch_order_array,
            ];

            if ($lunch_order_id) {
                $date_array = [];
                $user_data = [];
                $factory_data = [];
                $place_data = [];
                $eat_data = [];
                $days_data = [];
                $money_data = [];

                $lunch_order = LunchOrder::find($lunch_order_id);
                $lunch_setup = LunchSetup::where('semester', $lunch_order->semester)->first();


                $date_array = $this->get_order_date($lunch_order_id);

                $tea_dates = LunchTeaDate::where('lunch_order_id', $lunch_order_id)
                    ->where('lunch_factory_id', $factory->id)
                    ->where('enable', 'eat')
                    ->orderBy('lunch_place_id')
                    ->orderBy('order_date')
                    ->get();
                foreach ($tea_dates as $tea_date) {
                    $user_data[$tea_date->user->name][$tea_date->order_date]['enable'] = $tea_date->enable;
                    if (substr($tea_date->lunch_place_id, 0, 1) == "c") {
                        $place_data[$tea_date->user->name] = substr($tea_date->lunch_place_id, 1, 4) . "教室";
                    } else {
                        $place_data[$tea_date->user->name] = $tea_date->lunch_place->name;
                    }
                    $eat_data[$tea_date->user->name] = $tea_date->eat_style;
                    if ($tea_date->enable == "eat") {
                        if (!isset($days_data[$tea_date->user->name])) $days_data[$tea_date->user->name] = 0;
                        $days_data[$tea_date->user->name]++;
                        if (!isset($money_data[$tea_date->user->name])) $money_data[$tea_date->user->name] = 0;
                        $money_data[$tea_date->user->name] += $lunch_setup->teacher_money;
                    }
                }

                $teacher_money = $lunch_setup->teacher_money;

                $lunch_class_dates = LunchClassDate::where('semester', $lunch_order->semester)
                    ->where('lunch_factory_id', $factory->id)
                    ->orderBy('lunch_class_id')
                    ->orderBy('order_date')
                    ->get();

                $lunch_class_data = [];
                foreach ($lunch_class_dates as $lunch_class_date) {
                    $lunch_class_data[$lunch_class_date->lunch_class_id][$lunch_class_date->order_date][1] = $lunch_class_date->eat_style1;
                    $lunch_class_data[$lunch_class_date->lunch_class_id][$lunch_class_date->order_date][4] = $lunch_class_date->eat_style4;
                }

                $student_classes = StudentClass::where('semester', $lunch_order->semester)
                    ->orderBy('student_year')
                    ->orderBy('student_class')
                    ->get();

                $data = [
                    'factory' => $factory,
                    'lunch_order_id' => $lunch_order_id,
                    'lunch_order_array' => $lunch_order_array,
                    'date_array' => $date_array,
                    'user_data' => $user_data,
                    'place_data' => $place_data,
                    'eat_data' => $eat_data,
                    'days_data' => $days_data,
                    'money_data' => $money_data,
                    'teacher_money' => $teacher_money,
                    'lunch_class_dates' => $lunch_class_dates,
                    'lunch_class_data' => $lunch_class_data,
                    'student_classes' => $student_classes,
                ];
            }
        }
        return view('lunch_lists.factory', $data);
    }

    public function change_factory()
    {
        session(['factory' => null]);
        return redirect()->route('lunch_lists.factory');
    }




    public function get_order_date($lunch_order_id)
    {
        $order_dates = LunchOrderDate::where('lunch_order_id', $lunch_order_id)
            ->get();
        foreach ($order_dates as $order_date) {
            $date_array[$order_date->order_date] = $order_date->enable;
        }
        return $date_array;
    }
}
