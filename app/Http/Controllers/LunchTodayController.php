<?php

namespace App\Http\Controllers;

use App\LunchToday;
use Illuminate\Http\Request;

class LunchTodayController extends Controller
{
    public function index()
    {
        $lunch_todays = LunchToday::all();
        $data = [
            'lunch_todays'=>$lunch_todays,
        ];
        return view('lunch_todays.index',$data);
    }

    public function update(Request $request)
    {
        $lunch_today = LunchToday::find($request->input('id'));
        $s = get_url("https://fatraceschool.k12ea.gov.tw/offered/meal?SchoolId=".$request->input('school_id')."&KitchenId=all&period=2021-05-05");
        $school = json_decode($s,true);

        $att['school_id'] = $request->input('school_id');
        $att['school_name'] = $school['data'][0]['SchoolName'];
        $lunch_today->update($att);
        return redirect(route('lunch_todays.index'));
    }

    public function delete(LunchToday $lunch_today)
    {
        $att['school_id'] = null;
        $att['school_name'] = null;
        $lunch_today->update($att);
        return redirect(route('lunch_todays.index'));
    }

    public function return_date1(Request $request)
    {
        $att = $request->all();
        $s = get_url("https://fatraceschool.k12ea.gov.tw/offered/meal?SchoolId=".$att['school_id']."&KitchenId=all&period=".$att['date1']);
        $lunch_datas = json_decode($s,true);

        if(empty($lunch_datas['data'])){

            $result = "此日無資料";
            echo json_encode($result);
            return;

        }else{
            foreach($lunch_datas['data'] as $lunch_data){
                $kitchen_datas[$lunch_data['KitchenName']][$lunch_data['MenuTypeName']] =$lunch_data['BatchDataId'];
            }
            ksort($kitchen_datas);
            foreach($kitchen_datas as $k=>$v){
                foreach($v as $k1=>$v1){
                    $json = get_url("https://fatraceschool.k12ea.gov.tw/dish?BatchDataId={$v1}");
                    $dish = json_decode($json, true);
                    $kitchen_datas['dish'][$v1] = array();
                    foreach($dish['data'] as $d){
                        if(isset($d['DishType'])){
                            $a = [
                                'DishId'=>$d['DishId'],
                                'DishType'=>$d['DishType'],
                                'DishName'=>$d['DishName'],
                            ];
                            array_push($kitchen_datas['dish'][$v1],$a);
                        }
                    }
                }
            }

            $result =$kitchen_datas;

            echo json_encode($result);
            return;
        }

    }

    public function return_date2(Request $request)
    {
        $att = $request->all();
        $s = get_url("https://fatraceschool.k12ea.gov.tw/offered/meal?SchoolId=".$att['school_id']."&KitchenId=all&period=".$att['date2']);
        $lunch_datas = json_decode($s,true);

        if(empty($lunch_datas['data'])){

            $result = "此日無資料";
            echo json_encode($result);
            return;

        }else{
            foreach($lunch_datas['data'] as $lunch_data){
                $kitchen_datas[$lunch_data['KitchenName']][$lunch_data['MenuTypeName']] =$lunch_data['BatchDataId'];
            }
            ksort($kitchen_datas);
            foreach($kitchen_datas as $k=>$v){
                foreach($v as $k1=>$v1){
                    $json = get_url("https://fatraceschool.k12ea.gov.tw/dish?BatchDataId={$v1}");
                    $dish = json_decode($json, true);
                    $kitchen_datas['dish'][$v1] = array();
                    foreach($dish['data'] as $d){
                        if(isset($d['DishType'])){
                            $a = [
                                'DishId'=>$d['DishId'],
                                'DishType'=>$d['DishType'],
                                'DishName'=>$d['DishName'],
                            ];
                            array_push($kitchen_datas['dish'][$v1],$a);
                        }
                    }
                }
            }

            $result =$kitchen_datas;

            echo json_encode($result);
            return;
        }

    }

    public function return_date3(Request $request)
    {
        $att = $request->all();
        $s = get_url("https://fatraceschool.k12ea.gov.tw/offered/meal?SchoolId=".$att['school_id']."&KitchenId=all&period=".$att['date3']);
        $lunch_datas = json_decode($s,true);

        if(empty($lunch_datas['data'])){

            $result = "此日無資料";
            echo json_encode($result);
            return;

        }else{
            foreach($lunch_datas['data'] as $lunch_data){
                $kitchen_datas[$lunch_data['KitchenName']][$lunch_data['MenuTypeName']] =$lunch_data['BatchDataId'];
            }
            ksort($kitchen_datas);
            foreach($kitchen_datas as $k=>$v){
                foreach($v as $k1=>$v1){
                    $json = get_url("https://fatraceschool.k12ea.gov.tw/dish?BatchDataId={$v1}");
                    $dish = json_decode($json, true);
                    $kitchen_datas['dish'][$v1] = array();
                    foreach($dish['data'] as $d){
                        if(isset($d['DishType'])){
                            $a = [
                                'DishId'=>$d['DishId'],
                                'DishType'=>$d['DishType'],
                                'DishName'=>$d['DishName'],
                            ];
                            array_push($kitchen_datas['dish'][$v1],$a);
                        }
                    }
                }
            }

            $result =$kitchen_datas;

            echo json_encode($result);
            return;
        }

    }

    public function return_date4(Request $request)
    {
        $att = $request->all();
        $s = get_url("https://fatraceschool.k12ea.gov.tw/offered/meal?SchoolId=".$att['school_id']."&KitchenId=all&period=".$att['date4']);
        $lunch_datas = json_decode($s,true);

        if(empty($lunch_datas['data'])){

            $result = "此日無資料";
            echo json_encode($result);
            return;

        }else{
            foreach($lunch_datas['data'] as $lunch_data){
                $kitchen_datas[$lunch_data['KitchenName']][$lunch_data['MenuTypeName']] =$lunch_data['BatchDataId'];
            }
            ksort($kitchen_datas);
            foreach($kitchen_datas as $k=>$v){
                foreach($v as $k1=>$v1){
                    $json = get_url("https://fatraceschool.k12ea.gov.tw/dish?BatchDataId={$v1}");
                    $dish = json_decode($json, true);
                    $kitchen_datas['dish'][$v1] = array();
                    foreach($dish['data'] as $d){
                        if(isset($d['DishType'])){
                            $a = [
                                'DishId'=>$d['DishId'],
                                'DishType'=>$d['DishType'],
                                'DishName'=>$d['DishName'],
                            ];
                            array_push($kitchen_datas['dish'][$v1],$a);
                        }
                    }
                }
            }

            $result =$kitchen_datas;

            echo json_encode($result);
            return;
        }

    }


}
