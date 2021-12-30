<?php

namespace App\Http\Controllers;

use App\TeacherAbsent;
use App\TeacherAbsentOutlay;
use App\User;
use App\UserPower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAbsentController extends Controller
{
    public function __construct()
    {
        $module_setup = get_module_setup();
        if (!isset($module_setup['教師差假'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }

    public function index($select_semester=null)
    {
        $semesters = DB::table('teacher_absents')
            ->select(DB::raw('semester'))
            ->groupBy('semester')
            ->orderBy('semester')
            ->pluck('semester','semester')
            ->toArray();

        $semester = ($select_semester)?$select_semester:get_date_semester(date('Y-m-d'));

        $teacher_absents = TeacherAbsent::where('user_id',auth()->user()->id)
            ->where('semester',$semester)
            ->orderBy('id','DESC')
            ->paginate('40');

        $t_bs = TeacherAbsent::where('user_id',auth()->user()->id)
            ->where('semester',$semester)
            ->where('status','2')
            ->orderBy('id','DESC')
            ->get();

        $abs_kinds = config('chcschool.abs_kinds');
        foreach($abs_kinds as $k=>$v){
            $abs_kind_total[$k] = 0;
        }

        foreach($t_bs as $t_b){
            $abs_kind_total[$t_b->abs_kind] = $abs_kind_total[$t_b->abs_kind]+$t_b->day+($t_b->hour/8);
        }

        $class_dises = config('chcschool.class_dises');
        $user_name = get_user_name();
        $school_code = school_code();
        $data = [
            'abs_kind_total'=>$abs_kind_total,
            'semester'=>$semester,
            'semesters'=>$semesters,
            'abs_kinds'=>$abs_kinds,
            'class_dises'=>$class_dises,
            'teacher_absents'=>$teacher_absents,
            'user_name'=>$user_name,
            'school_code'=>$school_code,
        ];
        return view('teacher_absents.index',$data);
    }

    public function create()
    {
        $abs_kinds = config('chcschool.abs_kinds');
        $class_dises = config('chcschool.class_dises');
        $user_select = User::where('disable',null)
            ->where('username','<>','admin')
            ->where('username','<>',auth()->user()->username)
            ->orderBy('order_by')
            ->pluck('name','id')
            ->toArray();
        $data = [
            'abs_kinds'=>$abs_kinds,
            'class_dises'=>$class_dises,
            'user_select'=>$user_select,
        ];
        return view('teacher_absents.create',$data);
    }

    public function edit(TeacherAbsent $teacher_absent)
    {
        $abs_kinds = config('chcschool.abs_kinds');
        $class_dises = config('chcschool.class_dises');
        $user_select = User::where('disable',null)
            ->where('username','<>','admin')
            ->where('username','<>',auth()->user()->username)
            ->orderBy('order_by')
            ->pluck('name','id')
            ->toArray();

        $school_code = school_code();

        $data = [
            'teacher_absent'=>$teacher_absent,
            'abs_kinds'=>$abs_kinds,
            'class_dises'=>$class_dises,
            'user_select'=>$user_select,
            'school_code'=>$school_code,
        ];

        return view('teacher_absents.edit',$data);
    }

    public function admin_edit(TeacherAbsent $teacher_absent)
    {
        $abs_kinds = config('chcschool.abs_kinds');
        $class_dises = config('chcschool.class_dises');
        $user_select = User::where('disable',null)
            ->where('username','<>','admin')
            ->where('username','<>',auth()->user()->username)
            ->orderBy('order_by')
            ->pluck('name','id')
            ->toArray();

        $school_code = school_code();

        $data = [
            'teacher_absent'=>$teacher_absent,
            'abs_kinds'=>$abs_kinds,
            'class_dises'=>$class_dises,
            'user_select'=>$user_select,
            'school_code'=>$school_code,
        ];

        return view('teacher_absents.admin_edit',$data);
    }

    public function store(Request $request)
    {
        if($request->input('day')==null and $request->input('hour')==null){
            return back()->withErrors(['errors'=>['請假日數及時數不得同為空值']]);
        }

        $teacher_absent = TeacherAbsent::create($request->all());
        $att['class_file'] = null;
        $att['note_file'] = null;
        //處理檔案上傳
        $school_code = school_code();
        $folder = 'privacy/'. $school_code .'/teacher_absent/'.$teacher_absent->id;
        if ($request->hasFile('class_file')) {
            $file = $request->file('class_file');

            $info = [
                'original_filename' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
            ];

            $file->storeAs($folder, $info['original_filename']);
            $att['class_file'] = $info['original_filename'];
        }

        if ($request->hasFile('note_file')) {
            $file = $request->file('note_file');

            $info = [
                'original_filename' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
            ];

            $file->storeAs($folder, $info['original_filename']);
            $att['note_file'] = $info['original_filename'];
        }

        $start_date = explode('-',$teacher_absent->start_date);
        $att['month'] = $start_date[1];
        $teacher_absent->update($att);

        return redirect()->route('teacher_absents.index');

    }

    public function update(Request $request,TeacherAbsent $teacher_absent)
    {
        $teacher_absent->update($request->all());
        $start_date = explode('-',$teacher_absent->start_date);
        $att['month'] = $start_date[1];
        $att['status'] =1;
        $teacher_absent->update($att);
        return redirect()->route('teacher_absents.index');
    }

    public function admin_update(Request $request,TeacherAbsent $teacher_absent)
    {
        $teacher_absent->update($request->all());
        $start_date = explode('-',$teacher_absent->start_date);
        $att['month'] = $start_date[1];
        $teacher_absent->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function destroy(TeacherAbsent $teacher_absent)
    {
        if($teacher_absent->user_id == auth()->user()->id){
            $school_code = school_code();
            delete_dir(storage_path('app/privacy/'.$school_code.'/teacher_absent/'.$teacher_absent->id));
            $teacher_absent->delete();
        }

        return redirect()->route('teacher_absents.index');
    }

    public function delete_file($filename,TeacherAbsent $teacher_absent,$type)
    {
        $file = str_replace('&','/',$filename);
        $file = storage_path('app/privacy/'.$file);

        if(file_exists($file)){
            unlink($file);
        }

        $att[$type] = null;
        $teacher_absent->update($att);

        return redirect()->route('teacher_absents.edit',$teacher_absent->id);

    }

    public function deputy($select_semester=null)
    {
        $semesters = DB::table('teacher_absents')
            ->select(DB::raw('semester'))
            ->groupBy('semester')
            ->orderBy('semester')
            ->pluck('semester','semester')
            ->toArray();

        $semester = ($select_semester)?$select_semester:get_date_semester(date('Y-m-d'));

        $teacher_absents = TeacherAbsent::where('deputy_user_id',auth()->user()->id)
            ->where('semester',$semester)
            ->orderBy('id','DESC')
            ->paginate('40');
        $abs_kinds = config('chcschool.abs_kinds');
        $class_dises = config('chcschool.class_dises');
        $user_name = get_user_name();
        $school_code = school_code();
        $data = [
            'semester'=>$semester,
            'semesters'=>$semesters,
            'abs_kinds'=>$abs_kinds,
            'class_dises'=>$class_dises,
            'teacher_absents'=>$teacher_absents,
            'user_name'=>$user_name,
            'school_code'=>$school_code,
        ];
        return view('teacher_absents.deputy',$data);
    }


    public function check($type,TeacherAbsent $teacher_absent)
    {
        if($type=="deputy" and $teacher_absent->deputy_user_id == auth()->user()->id){
            $att['deputy_date'] = date('Y-m-d H:i:s');
            $teacher_absent->update($att);

            return redirect()->route('teacher_absents.deputy');
        }

        if($type=="check1"){
            $att['check1_user_id'] = auth()->user()->id;
            $att['check1_date'] = date('Y-m-d H:i:s');
            $teacher_absent->update($att);
            return redirect()->route('teacher_absents.sir');
        }

        if($type=="check2"){
            $att['check2_user_id'] = auth()->user()->id;
            $att['check2_date'] = date('Y-m-d H:i:s');
            $att['status'] = 2;
            $teacher_absent->update($att);
            return redirect()->route('teacher_absents.sir');
        }

        if($type=="check3"){
            $att['check3_user_id'] = auth()->user()->id;
            $att['check3_date'] = date('Y-m-d H:i:s');
            $teacher_absent->update($att);
            return redirect()->route('teacher_absents.sir');
        }

        if($type=="check4"){
            $att['check4_user_id'] = auth()->user()->id;
            $att['check4_date'] = date('Y-m-d H:i:s');
            $teacher_absent->update($att);
            return redirect()->route('teacher_absents.sir');
        }

    }

    public function sir($select_semester=null)
    {
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','A')
            ->first();
        $check_power['a'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','B')
            ->first();
        $check_power['b'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','C')
            ->first();
        $check_power['c'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','D')
            ->first();
        $check_power['d'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','E')
            ->first();
        $check_power['e'] = ($user_power)?1:0;

        $not_admin = "";
        if($check_power['a']+$check_power['b']+$check_power['c']+$check_power['d']+$check_power['e']==0){
            $not_admin = 1;
            $data = [
                'not_admin'=>$not_admin,
            ];
            return view('teacher_absents.sir',$data);
        }

        $semesters = DB::table('teacher_absents')
            ->select(DB::raw('semester'))
            ->groupBy('semester')
            ->orderBy('semester')
            ->pluck('semester','semester')
            ->toArray();

        $semester = ($select_semester)?$select_semester:get_date_semester(date('Y-m-d'));

        $teacher_absents = TeacherAbsent::where('semester',$semester)
            ->where('deputy_date','<>',null)
            ->orderBy('id','DESC')
            ->paginate('40');
        $abs_kinds = config('chcschool.abs_kinds');
        $class_dises = config('chcschool.class_dises');
        $user_name = get_user_name();
        $school_code = school_code();
        $data = [
            'not_admin'=>$not_admin,
            'check_power'=>$check_power,
            'semester'=>$semester,
            'semesters'=>$semesters,
            'abs_kinds'=>$abs_kinds,
            'class_dises'=>$class_dises,
            'teacher_absents'=>$teacher_absents,
            'user_name'=>$user_name,
            'school_code'=>$school_code,
        ];
        return view('teacher_absents.sir',$data);
    }

    public function total($select_semester=null,$select_month=null)
    {
        $semesters = DB::table('teacher_absents')
            ->select(DB::raw('semester'))
            ->groupBy('semester')
            ->orderBy('semester')
            ->pluck('semester','semester')
            ->toArray();

        $semester = ($select_semester)?$select_semester:get_date_semester(date('Y-m-d'));
        $month = ($select_month)?$select_month:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','A')
            ->first();
        $check_power['a'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','B')
            ->first();
        $check_power['b'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','C')
            ->first();
        $check_power['c'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','D')
            ->first();
        $check_power['d'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','E')
            ->first();
        $check_power['e'] = ($user_power)?1:0;

        $not_admin = "";
        $abs_kind_total=[];
        $teachers=[];
        $abs_kinds = config('chcschool.abs_kinds');


        if($check_power['a']+$check_power['b']+$check_power['c']+$check_power['d']+$check_power['e']==0){
            if($month==0){
                $t_bs = TeacherAbsent::where('semester',$semester)
                    ->where('user_id',auth()->user()->id)
                    ->where('status','2')
                    ->get();
            }else{
                $t_bs = TeacherAbsent::where('semester',$semester)
                    ->where('user_id',auth()->user()->id)
                    ->where('month',$month)
                    ->where('status','2')
                    ->get();
            }




            $teachers[auth()->user()->id] = auth()->user()->name;

            foreach($abs_kinds as $k=>$v){
                $abs_kind_total[auth()->user()->id][$k] = 0;
            }

            foreach($t_bs as $t_b){
                $abs_kind_total[auth()->user()->id][$t_b->abs_kind] = $abs_kind_total[auth()->user()->id][$t_b->abs_kind]+$t_b->day+($t_b->hour/8);
            }
        }else{
            if($month==0){
                $t_bs = TeacherAbsent::where('semester',$semester)
                    ->where('status','2')
                    ->get();
            }else{
                $t_bs = TeacherAbsent::where('semester',$semester)
                    ->where('month',$month)
                    ->where('status','2')
                    ->get();
            }


            foreach($t_bs as $t_b){
                foreach($abs_kinds as $k=>$v){
                    if(!isset($abs_kind_total[$t_b->user_id][$k])){
                        $abs_kind_total[$t_b->user_id][$k] = 0;
                    }
                }
                $teachers[$t_b->user_id] = $t_b->user->name;
                $abs_kind_total[$t_b->user_id][$t_b->abs_kind] = $abs_kind_total[$t_b->user_id][$t_b->abs_kind]+$t_b->day+($t_b->hour/8);
            }
        }



        $monthes = [
            '0'=>'全學期',
            '1'=>'一月',
            '2'=>'二月',
            '3'=>'三月',
            '4'=>'四月',
            '5'=>'五月',
            '6'=>'六月',
            '7'=>'七月',
            '8'=>'八月',
            '9'=>'九月',
            '10'=>'十月',
            '11'=>'十一月',
            '12'=>'十二月',
        ];

        $data = [
            'semesters'=>$semesters,
            'semester'=>$semester,
            'monthes'=>$monthes,
            'month'=>$month,
            'abs_kinds'=>$abs_kinds,
            'abs_kind_total'=>$abs_kind_total,
            'teachers'=>$teachers,
        ];

        return view('teacher_absents.total',$data);

    }

    public function list($select_semester=null,$select_teacher=null,$select_abs=null,$select_month=null)
    {
        $semesters = DB::table('teacher_absents')
            ->select(DB::raw('semester'))
            ->groupBy('semester')
            ->orderBy('semester')
            ->pluck('semester','semester')
            ->toArray();

        $semester = ($select_semester)?$select_semester:get_date_semester(date('Y-m-d'));

        $monthes = [
            '0'=>'全學期',
            '1'=>'一月',
            '2'=>'二月',
            '3'=>'三月',
            '4'=>'四月',
            '5'=>'五月',
            '6'=>'六月',
            '7'=>'七月',
            '8'=>'八月',
            '9'=>'九月',
            '10'=>'十月',
            '11'=>'十一月',
            '12'=>'十二月',
        ];

        $month = ($select_month)?$select_month:0;
        $teacher = ($select_teacher)?$select_teacher:0;
        $abs = ($select_abs)?$select_abs:0;
        $abses = config('chcschool.abs_kinds');

        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','A')
            ->first();
        $check_power['a'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','B')
            ->first();
        $check_power['b'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','C')
            ->first();
        $check_power['c'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','D')
            ->first();
        $check_power['d'] = ($user_power)?1:0;
        $user_power = UserPower::where('name','教師差假')
            ->where('user_id',auth()->user()->id)
            ->where('type','E')
            ->first();
        $check_power['e'] = ($user_power)?1:0;

        $not_admin = "";

        if($check_power['a']+$check_power['b']+$check_power['c']+$check_power['d']+$check_power['e']==0) {
            $data = [
                'not_admin'=>$not_admin,
            ];
            return view('teacher_absents.list',$data);
        }else{
            $not_admin=1;

            $teachers = get_user_name();

            if($teacher==0 and $abs==0 and $month==0){
                $teacher_absents = TeacherAbsent::where('semester',$semester)
                    ->where('status','2')
                    ->orderBy('id','DESC')
                    ->get();
            }

            if($teacher==0 and $abs==0 and $month !=0){
                $teacher_absents = TeacherAbsent::where('semester',$semester)
                    ->where('status','2')
                    ->where('month',$month)
                    ->orderBy('id','DESC')
                    ->get();
            }

            if($teacher==0 and $abs !=0 and $month==0){
                $teacher_absents = TeacherAbsent::where('semester',$semester)
                    ->where('status','2')
                    ->where('abs_kind',$abs)
                    ->orderBy('id','DESC')
                    ->get();
            }

            if($teacher !=0 and $abs==0 and $month==0){
                $teacher_absents = TeacherAbsent::where('semester',$semester)
                    ->where('status','2')
                    ->where('user_id',$teacher)
                    ->orderBy('id','DESC')
                    ->get();
            }

            if($teacher==0 and $abs !=0 and $month !=0){
                $teacher_absents = TeacherAbsent::where('semester',$semester)
                    ->where('status','2')
                    ->where('month',$month)
                    ->where('abs_kind',$abs)
                    ->orderBy('id','DESC')
                    ->get();
            }

            if($teacher !=0 and $abs !=0 and $month==0){
                $teacher_absents = TeacherAbsent::where('semester',$semester)
                    ->where('status','2')
                    ->where('user_id',$teacher)
                    ->where('abs_kind',$abs)
                    ->orderBy('id','DESC')
                    ->get();
            }

            if($teacher !=0 and $abs==0 and $month !=0){
                $teacher_absents = TeacherAbsent::where('semester',$semester)
                    ->where('status','2')
                    ->where('user_id',$teacher)
                    ->where('month',$month)
                    ->orderBy('id','DESC')
                    ->get();
            }

            if($teacher !=0 and $abs !=0 and $month !=0){
                $teacher_absents = TeacherAbsent::where('semester',$semester)
                    ->where('status','2')
                    ->where('user_id',$teacher)
                    ->where('month',$month)
                    ->where('abs_kind',$abs)
                    ->orderBy('id','DESC')
                    ->get();
            }

            $user_name = get_user_name();
            $abs_kinds = config('chcschool.abs_kinds');
            $class_dises = config('chcschool.class_dises');
            $school_code = school_code();
            $data = [
                'not_admin'=>$not_admin,
                'semesters'=>$semesters,
                'semester'=>$semester,
                'monthes'=>$monthes,
                'month'=>$month,
                'teacher'=>$teacher,
                'teachers'=>$teachers,
                'abs'=>$abs,
                'abses'=>$abses,
                'teacher_absents'=>$teacher_absents,
                'user_name'=>$user_name,
                'abs_kinds'=>$abs_kinds,
                'class_dises'=>$class_dises,
                'school_code'=>$school_code,
            ];

            return view('teacher_absents.list',$data);
        }
    }

    public function back(TeacherAbsent $teacher_absent)
    {
       $data = [
           'teacher_absent'=>$teacher_absent,
       ];
        return view('teacher_absents.back',$data);
    }

    public function store_back(Request $request,TeacherAbsent $teacher_absent)
    {
        $att['deputy_date'] = null;
        $att['check1_user_id'] = null;
        $att['check1_date'] = null;
        $att['check2_user_id'] = null;
        $att['check2_date'] = null;
        $att['check3_user_id'] = null;
        $att['check3_date'] = null;
        $att['check4_user_id'] = null;
        $att['check4_date'] = null;
        $att['status'] = 3;
        $att['reason'] = $request->input('title')."退回：".$request->input('back').'(請修改)--'.$teacher_absent->reason;
        $teacher_absent->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function travel($select_semester=null)
    {
        $semesters = DB::table('teacher_absents')
            ->select(DB::raw('semester'))
            ->groupBy('semester')
            ->orderBy('semester')
            ->pluck('semester','semester')
            ->toArray();

        $semester = ($select_semester)?$select_semester:get_date_semester(date('Y-m-d'));

        $teacher_absents = TeacherAbsent::where('semester',$semester)
            ->where('user_id',auth()->user()->id)
            ->where('status','2')
            ->where('abs_kind','52')
            ->orderBy('id','DESC')
            ->get();

        $abs_kinds = config('chcschool.abs_kinds');
        $class_dises = config('chcschool.class_dises');
        $user_name = get_user_name();
        $school_code = school_code();

        $data = [
            'semester'=>$semester,
            'semesters'=>$semesters,
            'abs_kinds'=>$abs_kinds,
            'class_dises'=>$class_dises,
            'teacher_absents'=>$teacher_absents,
            'user_name'=>$user_name,
            'school_code'=>$school_code,
        ];
        return view('teacher_absents.travel',$data);
    }

    public function outlay(TeacherAbsent $teacher_absent)
    {

        $data = [
            'teacher_absent'=>$teacher_absent,
        ];
        return view('teacher_absents.outlay',$data);
    }

    public function store_outlay(Request $request)
    {
        TeacherAbsentOutlay::create($request->all());
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function delete_outlay(TeacherAbsentOutlay $teacher_absent_outlay)
    {
        if($teacher_absent_outlay->teacher_absent->user_id == auth()->user()->id){
            $teacher_absent_outlay->delete();
        }

        return redirect()->route('teacher_absents.travel');
    }

    public function edit_outlay(TeacherAbsentOutlay $teacher_absent_outlay)
    {
        $data = [
            'teacher_absent_outlay'=>$teacher_absent_outlay,
        ];

        return view('teacher_absents.outlay_edit',$data);
    }

    public function update_outlay(Request $request,TeacherAbsentOutlay $teacher_absent_outlay)
    {
        if($teacher_absent_outlay->teacher_absent->user_id == auth()->user()->id) {
            $teacher_absent_outlay->update($request->all());
        }
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function travel_print(Request $request)
    {
        $travels = $request->input('travels');
        $teacher_absents=[];
        if(!empty($travels)){
            foreach($travels as $k=>$v){
                $select_travel[] = $k;
            }
            $teacher_absents = TeacherAbsent::whereIn('id',$select_travel)
                ->get();
        }

        $user_name = get_user_name();
        $data = [
            'teacher_absents'=>$teacher_absents,
            'user_name'=>$user_name,
        ];
        return view('teacher_absents.travel_print',$data);
    }
}
