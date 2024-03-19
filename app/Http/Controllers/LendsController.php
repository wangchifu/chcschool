<?php

namespace App\Http\Controllers;

use App\User;
use App\Setup;
use App\LendClass;
use App\LendItem;
use App\LendOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use PHPExcel_IOFactory;
use PHPExcel;

class LendsController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['借用系統'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index($lend_class_id=null,$this_date=null)
    {
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        $lend_classes = LendClass::all();
        $lend_class_array = [];
        

        if($lend_class_id==null){
            $now_lend_class = LendClass::orderBy('id')->first();
            if(!empty($now_lend_class->id)){
                $lend_class_id = $now_lend_class->id;
            }
        }

        if($this_date == null){
            $this_date = date('Y-m-d');
            
        }
        $this_dt = new Carbon($this_date);

        $lend_orders = LendOrder::where('lend_date','<=',$this_date)
        ->where('back_date','>=',$this_date)
        ->get();
        
        $lend_item_data = [];
        $all_lend_num = [];

        foreach($lend_orders as $lend_order){
            $lend_item_data[$lend_order->lend_item_id][$lend_order->id][$lend_order->user->name]['num'] = $lend_order->num;
            $lend_item_data[$lend_order->lend_item_id][$lend_order->id][$lend_order->user->name]['lend_date'] = $lend_order->lend_date;
            $lend_item_data[$lend_order->lend_item_id][$lend_order->id][$lend_order->user->name]['back_date'] = $lend_order->back_date;
            $lend_item_data[$lend_order->lend_item_id][$lend_order->id][$lend_order->user->name]['ps'] = $lend_order->ps;
            if(!isset($all_lend_num[$lend_order->lend_item_id])) $all_lend_num[$lend_order->lend_item_id] = 0;
            $all_lend_num[$lend_order->lend_item_id] += $lend_order->num;
        }
        
        $lend_items = [];
        if($lend_class_id != null){
            $lend_items = LendItem::where('lend_class_id',$lend_class_id)
            ->where('enable',1)
            ->get();
        }

                

        $data = [
            'admin'=>$admin,
            'lend_class_id'=>$lend_class_id,
            'this_date'=>$this_date,
            'this_dt'=>$this_dt,
            'lend_classes'=>$lend_classes,
            'lend_items'=>$lend_items,
            'lend_item_data'=>$lend_item_data,
            'all_lend_num'=>$all_lend_num,
        ];
        $string = $_SERVER['REQUEST_URI'];
        if(stripos($string,"lends/index")){
            return view('lends.index', $data);
        }
        if(stripos($string,"lends/clean")){
            return view('lends.index_clean', $data);
        }
        
        
    }

    public function admin($lend_class_id=null)
    {        
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        if(!$admin) return back();

        $lend_classes = LendClass::where('user_id',auth()->user()->id)->get();
        $lend_class_array = [];
        foreach($lend_classes as $lend_class){
            $lend_class_array[$lend_class->id] = $lend_class->name;
        }


        if($lend_class_id==null){
            $now_lend_class = LendClass::where('user_id',auth()->user()->id)
            ->orderBy('id')
            ->first();
            if(!empty($now_lend_class->id)){
                $lend_class_id = $now_lend_class->id;
            }
        }
        
        $lend_items = [];
        if($lend_class_id){
            $lend_items =  LendItem::where('lend_class_id',$lend_class_id)->get();
        }
        

        

        $data = [
            'admin'=>$admin,
            'lend_class_id'=>$lend_class_id,
            'lend_class_array'=>$lend_class_array,
            'lend_items'=>$lend_items,
            'lend_classes'=>$lend_classes,
        ];
        return view('lends.admin', $data);
    }

    public function store_class(Request $request){
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        if(!$admin) return back();

        $request->validate([
            'name' => 'required',
          ]);

        $att = $request->all();
        $att['user_id'] = auth()->user()->id;
        LendClass::create($att);
        return back();
    }

    public function update_class(Request $request,LendClass $lend_class){
        if($lend_class->user_id != auth()->user()->id) return back();
        $att['name'] = $request->input('name');
        $att['ps'] = $request->input('ps');
        $lend_class->update($att);
        return back();
    }

    public function delete_class(LendClass $lend_class){
        if($lend_class->user_id != auth()->user()->id) return back();
        $lend_items = LendItem::where('lend_class_id',$lend_class->id)->get();
        foreach($lend_items as $lend_item){
            LendOrder::where('lend_item_id',$lend_item->id)->delete();
            $lend_item->delete();
        }
        
        $lend_class->delete();
        return back();
    }

    public function store_item(Request $request){
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        if(!$admin) return back();

        $request->validate([
            'name' => 'required',
            'num' => 'required',
          ]);

        $att = $request->all();
        
        $att['lend_sections'] =serialize($att['lend_sections']);
        $att['user_id'] = auth()->user()->id;
       
        LendItem::create($att);
        return back();
    }

    public function delete_item(LendItem $lend_item){
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        if(!$admin) return back();
        if($lend_item->user_id != auth()->user()->id) return back();

        $lend_item->delete();
        return back();
    }

    public function admin_edit(LendItem $lend_item,$lend_class_id=null){
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        if(!$admin) return back();
        if($lend_item->user_id != auth()->user()->id) return back();

        $lend_classes = LendClass::where('user_id',auth()->user()->id)->get();
        foreach($lend_classes as $lend_class){
            $lend_class_array[$lend_class->id] = $lend_class->name;
        }


        if($lend_class_id==null){
            $now_lend_class = LendClass::where('user_id',auth()->user()->id)
            ->orderBy('id')
            ->first();
            if(!empty($now_lend_class->id)){
                $lend_class_id = $now_lend_class->id;
            }
        }
        
        $lend_items = [];
        if($lend_class_id){
            $lend_items =  LendItem::where('lend_class_id',$lend_class_id)->get();
        }
        

        

        $data = [
            'admin'=>$admin,
            'lend_class_id'=>$lend_class_id,
            'lend_class_array'=>$lend_class_array,
            'lend_items'=>$lend_items,
            'lend_item'=>$lend_item,
        ];
        return view('lends.admin_edit', $data);    
    }

    public function update_item(Request $request,LendItem $lend_item){
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        if(!$admin) return back();

        $request->validate([
            'name' => 'required',
            'num' => 'required',
          ]);

        $att = $request->all();
        $att['lend_sections'] =serialize($att['lend_sections']);

        $lend_item->update($att);
        return redirect()->route('lends.admin',$lend_item->lend_class_id);
    }

    public function check_item_num(LendItem $lend_item)
    {
        $result['owner_user_id'] = $lend_item->user_id;
        $result['num'] = $lend_item->num;
        $result['lend_sections'] = unserialize($lend_item->lend_sections);
        $result['section_array'] = config('chcschool.lend_sections');

        echo json_encode($result);
        return;

    }

    public function check_order_out($this_date = null,$action=null)
    {

        $lend_orders = LendOrder::where($action,$this_date)
        ->where('owner_user_id',auth()->user()->id)
        ->get();
        $lend_sections_array = config('chcschool.lend_sections');
        $result = [];
        foreach($lend_orders as $lend_order){
            $result[$lend_order->id]['created_at'] = substr($lend_order->created_at,0,19);
            $result[$lend_order->id]['lend_item'] = $lend_order->lend_item->name;
            $result[$lend_order->id]['num'] = $lend_order->num;
            $result[$lend_order->id]['lend_date'] = $lend_order->lend_date;
            $result[$lend_order->id]['lend_section'] = $lend_sections_array[$lend_order->lend_section];
            $result[$lend_order->id]['back_date'] = $lend_order->back_date;
            $result[$lend_order->id]['back_section'] = $lend_sections_array[$lend_order->back_section];
            $result[$lend_order->id]['user'] = $lend_order->user->name;
            $result[$lend_order->id]['ps'] = $lend_order->ps;
        }        
        echo json_encode($result);
        return;

    }

    public function check_order_out_clean($this_date = null,$action=null)
    {

        $lend_orders = LendOrder::where($action,$this_date)
        ->get();
        $lend_sections_array = config('chcschool.lend_sections');
        $result = [];
        foreach($lend_orders as $lend_order){
            $result[$lend_order->id]['created_at'] = substr($lend_order->created_at,0,19);
            $result[$lend_order->id]['lend_item'] = $lend_order->lend_item->name;
            $result[$lend_order->id]['num'] = $lend_order->num;
            $result[$lend_order->id]['lend_date'] = $lend_order->lend_date;
            $result[$lend_order->id]['lend_section'] = $lend_sections_array[$lend_order->lend_section];
            $result[$lend_order->id]['back_date'] = $lend_order->back_date;
            $result[$lend_order->id]['back_section'] = $lend_sections_array[$lend_order->back_section];
            $result[$lend_order->id]['user'] = $lend_order->user->name;
            $result[$lend_order->id]['ps'] = $lend_order->ps;
        }        
        echo json_encode($result);
        return;

    }

    public function order(Request $request){
        $att = $request->all();
        $att['user_id'] = auth()->user()->id;

        if($att['lend_date'] <= date('Y-m-d')) return back()->withErrors(['date_error' => ['開始時間已是過去式，也不能是今天']]);
        if($att['lend_date'].'-'.$att['lend_section'] >= $att['back_date'].'-'.$att['back_section'] ) return back()->withErrors(['date_error' => ['歸還日期比借用日期還早或同時']]);

        //列出借用期間的日期
        $dt = new Carbon($att['lend_date']);
        $dt2 = new Carbon($att['back_date']);
        for($i=1;$i<=365;$i++){
            $dt_array[$i] = $dt->toDateString();
            if($dt==$dt2) break;
            $dt = new Carbon($dt->addDay());
        }

        //檢查是否超過數目
        $check_num = [];
        foreach($dt_array as $k=>$v){
            $lend_orders = LendOrder::where('lend_date','<=',$v)
            ->where('back_date','>=',$v)
            ->where('lend_item_id',$att['lend_item_id'])
            ->get();
            foreach($lend_orders as $lend_order){
                if(!isset($check_num[$v])) $check_num[$v]=0;
                $check_num[$v] += $lend_order->num;
            }
        }
        $lend_item = LendItem::find($att['lend_item_id']);
        foreach($check_num as $k=>$v){
            if($v+$att['num'] > $lend_item->num){
                return back()->withErrors(['date_error' => [$k.' 已被借用了數量： '.$v.' ，無法如你所願！']]);
                break;
            }
        }


        $lend_order = LendOrder::create($att);

        if(!empty($lend_order->owner_user->line_key)){           
            $lend_section_array = config('chcschool.lend_sections');
            $ps = ($lend_order->ps)?"\n備註：".$lend_order->ps:null;            
            $string =  auth()->user()->name."在借用系統登記\n\n".$lend_order->lend_item->name." 數量：".$lend_order->num."\n於 ".$lend_order->lend_date." ".$lend_section_array[$lend_order->lend_section]." 來借\n於 ".$lend_order->back_date." ".$lend_section_array[$lend_order->back_section]." 來還\n".$ps;
            line_notify($lend_order->owner_user->line_key,$string);
        }
        

        if($att['to_go']=="index"){
            return redirect()->route('lends.index',['lend_class_id'=>$lend_item->lend_class_id,'this_date'=>$att['lend_date']]);
            
        }
        if($att['to_go']=="clean"){
            return redirect()->route('lends.clean',['lend_class_id'=>$lend_item->lend_class_id,'this_date'=>$att['lend_date']]);
            
        }
        
    }

    function delete_my_order(LendOrder $lend_order){
        if($lend_order->user_id != auth()->user()->id) return back();
        $lend_order->delete();
        return back();
    }

    function delete_order(LendOrder $lend_order){    
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        if(!$admin) return back();

        $lend_order->delete();
        return back();
    }

    function update_other_order(Request $request,LendOrder $lend_order){
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        if(!$admin) return back();

        $att = $request->all();
 
        $lend_order->update($att);
        return back();
    }

    function list(){
        $admin = check_power('借用系統', 'A', auth()->user()->id);
        if(!$admin) return back();

        $lend_orders = LendOrder::where('owner_user_id',auth()->user()->id)
            ->orderBy('id','DESC')
            ->paginate(20);
        
        $lend_orders2 = LendOrder::where('owner_user_id',auth()->user()->id)
            ->where('lend_date',date('Y-m-d'))
            ->get();

        $lend_orders3 = LendOrder::where('owner_user_id',auth()->user()->id)
            ->where('back_date',date('Y-m-d'))
            ->get();        
        $data = [
            'admin'=>$admin,
            'lend_orders'=>$lend_orders,
            'lend_orders2'=>$lend_orders2,
            'lend_orders3'=>$lend_orders3,
            'sections_array'=>config('chcschool.lend_sections'),
        ];
        return view('lends.list',$data);
    }

    function list_clean(){

        $lend_orders = LendOrder::orderBy('lend_item_id')->orderBy('id','DESC')
            ->paginate(20);
        
        $lend_orders2 = LendOrder::where('lend_date',date('Y-m-d'))
            ->get();

        $lend_orders3 = LendOrder::where('back_date',date('Y-m-d'))
            ->get();        
        $data = [
            'lend_orders'=>$lend_orders,
            'lend_orders2'=>$lend_orders2,
            'lend_orders3'=>$lend_orders3,
            'sections_array'=>config('chcschool.lend_sections'),
        ];
        return view('lends.list_clean',$data);
    }

    function my_list(){
        $admin = check_power('借用系統', 'A', auth()->user()->id);

        $lend_orders = LendOrder::where('user_id',auth()->user()->id)
            ->orderBy('id','DESC')
            ->paginate(20);
        $data = [
            'admin'=>$admin,
            'lend_orders'=>$lend_orders,
            'sections_array'=>config('chcschool.lend_sections'),
        ];
        return view('lends.my_list',$data);
    }

    function store_line_notify(Request $request){
        $att['line_key'] =  $request->input('line_key');
        $user = User::where('id',auth()->user()->id)->first();
        $user->update($att);
        return redirect()->route('lends.list');
    }

    function download_excel(Request $request){
        $this_date = $request->input('this_date');
        $lend_orders1 = LendOrder::where('lend_date',$this_date)
            ->get();

        $lend_orders2 = LendOrder::where('back_date',$this_date)
            ->get(); 

            $lend_sections = config('chcschool.lend_sections');
            $n = 0 ;
        foreach($lend_orders1 as $lend_order){
            $data[$n] = [
                $this_date => "要借出",
                '第幾節下課' => $lend_sections[$lend_order->lend_section],
                '借用人' => $lend_order->user->name,
                '借用物品' =>  $lend_order->lend_item->name,
                '數量' => $lend_order->num,
                '借用期間' => $lend_order->lend_date.'~'.$lend_order->back_date,
            ];
            $n++;
        }
        $data[$n] = [
            $this_date => '--',
            '第幾節下課' => '--',
            '借用人' => '--',
            '借用物品' =>  '--',
            '數量' => '--',
            '借用期間' => '--',
        ];
        $n++;
        foreach($lend_orders2 as $lend_order){
            $data[$n] = [
                $this_date => "要歸還",
                '第第幾節下課幾節' => $lend_sections[$lend_order->back_section],
                '借用人' => $lend_order->user->name,
                '借用物品' =>  $lend_order->lend_item->name,
                '數量' => $lend_order->num,
                '借用期間' => $lend_order->lend_date.'~'.$lend_order->back_date,
            ];
            $n++;
        }
        $list = collect($data);

        return (new FastExcel($list))->download($this_date.'_借還單.xlsx');
            
    }
    
}
