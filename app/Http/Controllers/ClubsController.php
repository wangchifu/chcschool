<?php

namespace App\Http\Controllers;

use App\Club;
use App\ClubRegister;
use App\ClubSemester;
use App\ClubStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ClubsController extends Controller
{
    public function __construct()
    {
        $module_setup = get_module_setup();
        if (!isset($module_setup['社團報名'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }

    public function index()
    {
        $club_semesters = ClubSemester::orderby('semester')->get();
        $data = [
            'club_semesters'=>$club_semesters
        ];
        return view('clubs.index',$data);
    }

    public function semester_create()
    {
        return view('clubs.create');
    }

    public function semester_store(Request $request)
    {
        $semester= $request->input('semester');
        $check = ClubSemester::where('semester',$semester)->first();

        if(!$check){
            $att = $request->all();
            ClubSemester::create($att);
        }else{
            return back()->withErrors(['errors'=>[$semester.'學期已經有設定了！']]);
        }

        return redirect()->route('clubs.index');
    }

    public function semester_edit(ClubSemester $club_semester)
    {
        $data = [
            'club_semester'=>$club_semester
        ];
        return view('clubs.edit',$data);
    }

    public function semester_update(Request $request,ClubSemester $club_semester)
    {
        $att = $request->all();
        $club_semester->update($att);
        return redirect()->route('clubs.index');
    }

    public function setup($semester=null)
    {
        $club_semesters_array = ClubSemester::orderby('semester','DESC')->pluck('semester','semester')->toArray();
        $clubs = [];
        if($semester == null){
            $s = ClubSemester::orderBy('semester','DESC')->first();
            $semester = $s->semester;
        }
        if($semester){
            $clubs = Club::where('semester',$semester)->get();
        }

        $data = [
            'club_semesters_array'=>$club_semesters_array,
            'clubs'=>$clubs,
            'semester'=>$semester,
        ];
        return view('clubs.setup',$data);
    }

    public function club_create($semester)
    {
        $data = [
            'semester'=>$semester
        ];
        return view('clubs.club_create',$data);
    }

    public function club_store(Request $request)
    {
        $semester= $request->input('semester');
        $no = $request->input('no');
        $name = $request->input('name');
        $check1 = Club::where('semester',$semester)
            ->where('no',$no)
            ->first();
        $check2 = Club::where('semester',$semester)
            ->where('no',$no)
            ->where('name',$name)
            ->first();
        if($check1 or $check2){
            return back()->withErrors(['errors'=>[$request->no.'此編號或社團已經有設定了！']]);
        }else{
            $att = $request->all();
            $s1 = $att['start1_time1']."-".$att['start1_time2']."-".$att['start1_time3'];
            $s2 = $att['start2_time1']."-".$att['start2_time2']."-".$att['start2_time3'];
            if($att['start2_time1']==="0"){
                $att['start_time'] =$s1;
            }else{
                $att['start_time'] =$s1.';'.$s2;
            }

            Club::create($att);

            return redirect()->route('clubs.setup',$semester);
        }

    }

    public function club_copy(Request $request)
    {
        $clubs1 = Club::where('semester',$request->input('semester1'))->get();
        foreach($clubs1 as $club){
            $att['no'] = $club->no;
            $att['semester'] = $request->input('semester2');
            $att['name'] = $club->name;
            $att['contact_person'] = $club->contact_person;
            $att['telephone_num'] = $club->telephone_num;
            $att['money'] = $club->money;
            $att['people'] = $club->people;
            $att['teacher_info'] = $club->teacher_info;
            $att['start_date'] = $club->start_date;
            $att['start_time'] = $club->start_time;
            $att['place'] = $club->place;
            $att['ps'] = $club->ps;
            $att['taking'] = $club->taking;
            $att['prepare'] = $club->prepare;
            $att['year_limit'] = $club->year_limit;

            Club::create($att);
        }

        return redirect()->route('clubs.setup',$att['semester']);
    }

    public function club_edit(Club $club)
    {
        $data = [
            'club'=>$club
        ];

        return view('clubs.club_edit',$data);
    }

    public function club_update(Request $request,Club $club)
    {
        $att = $request->all();
        $s1 = $att['start1_time1']."-".$att['start1_time2']."-".$att['start1_time3'];
        $s2 = $att['start2_time1']."-".$att['start2_time2']."-".$att['start2_time3'];
        if($att['start2_time1']==="0"){
            $att['start_time'] =$s1;
        }else{
            $att['start_time'] =$s1.';'.$s2;
        }
        $club->update($att);
        return redirect()->route('clubs.setup',$club->semester);
    }

    public function club_delete(Club $club)
    {
        $club->delete();
        return redirect()->route('clubs.setup',$club->semester);
    }

    public function stu_adm($semester)
    {
        $club_students = ClubStudent::where('semester',$semester)
            ->orderBy('class_num')
            ->get();
        $data = [
            'club_students'=>$club_students,
            'semester'=>$semester,
        ];
        return view('clubs.stu_adm',$data);
    }

    public function stu_import(Request $request,$semester)
    {
        if ($request->hasFile('file')) {

            ClubStudent::where('semester',$semester)->delete();

            $file = $request->file('file');
            $collection = (new FastExcel)->import($file);
            foreach($collection as $k=>$v){
                $att['semester'] = $semester;
                $att['no'] = $v['學號'];
                $att['name'] = $v['姓名'];
                $att['pwd'] = date_format($v['生日(西元)'], 'Ymd');
                $att['class_num'] = $v['班級(數字)'].sprintf("%02s",$v['座號']);
                $att['birthday'] = date_format($v['生日(西元)'], 'Ymd');
                ClubStudent::create($att);
            }
        }

        return redirect()->route('clubs.stu_adm',$semester);
    }

    public function stu_create($semester)
    {
        $data = [
            'semester'=>$semester
        ];

        return view('clubs.stu_create',$data);
    }

    public function stu_store(Request $request,$semester)
    {
        $att = $request->all();

        $check = ClubStudent::where('no',$att['no'])->first();
        if($check){
            return back()->withErrors(['errors'=>[$att['no'].' 此學號已經有設定了！']]);
        }else{
            $att['pwd'] = $att['birthday'];
            $att['semester'] = $semester;
            ClubStudent::create($att);
            return redirect()->route('clubs.stu_adm',$semester);
        }
    }

    public function stu_edit(ClubStudent $club_student)
    {
        $data = [
            'club_student'=>$club_student,
        ];
        return view('clubs.stu_edit',$data);
    }

    public function stu_update(Request $request,ClubStudent $club_student)
    {
        $att = $request->all();

        $club_student->update($att);
        return redirect()->route('clubs.stu_adm',$club_student->semester);

    }

    public function stu_delete(ClubStudent $club_student)
    {
        $club_student->delete();
        return redirect()->route('clubs.stu_adm',$club_student->semester);
    }

    public function report()
    {
        return view('clubs.report');
    }

    public function semester_select()
    {
        $club_semesters = ClubSemester::orderBy('semester')->get();
        $data = [
            'club_semesters'=>$club_semesters,
        ];
        return view('clubs.semester_select',$data);
    }

    public function parents_login($semester)
    {
        $club_semester = ClubSemester::where('semester',$semester)->first();
        if(date('Ymd') >= str_replace('-','',$club_semester->start_date) and date('Ymd') <= str_replace('-','',$club_semester->stop_date)){
            $data = [
                'semester'=>$semester,
            ];
            return view('clubs.parents_login',$data);
        }else{
            return back();
        }

    }

    public function do_login(Request $request)
    {
        if($request->input('class_num')){
            $check = ClubStudent::where('class_num',$request->input('class_num'))
                ->where('semester',$request->input('semester'))
                ->first();

            if(!$check){
                return back()->withErrors(['error'=>['查無此帳號！']]);
            }else{
                if($request->input('pwd') != $check->pwd){
                    return back()->withErrors(['error'=>['密碼錯誤！']]);
                }else{
                    session(['parents'=>$check->id]);
                    return redirect()->route('clubs.parents_do');
                };
            }

        }
    }

    public function parents_do()
    {
        if(empty(session('parents'))){
            return redirect()->route('clubs.semester_select');
        }
        $user = ClubStudent::where('id',session('parents'))
            ->first();

        $clubs = Club::where('semester',$user->semester)->get();

        $club_semester = ClubSemester::where('semester',$user->semester)
            ->first();

        $data = [
            'user'=>$user,
            'clubs'=>$clubs,
            'club_semester'=>$club_semester,
        ];
        return view('clubs.parents_do',$data);
    }

    public function parents_logout()
    {
        session(['parents'=>null]);
        return redirect()->route('clubs.semester_select');
    }

    public function change_pwd()
    {
        if(empty(session('parents'))){
            return redirect()->route('clubs.semester_select');
        }
        $user = ClubStudent::where('id',session('parents'))
            ->first();
        $data = [
            'user'=>$user,
        ];
        return view('clubs.change_pwd',$data);
    }

    public function change_pwd_do(Request $request)
    {
        $user = ClubStudent::where('id',session('parents'))
            ->first();

        if($request->input('password0') != $user->pwd){
            return back()->withErrors(['error'=>['舊密碼錯誤！你不是本人！？']]);
        }
        if($request->input('password1') != $request->input('password2')){
            return back()->withErrors(['error'=>['兩次新密碼不相同']]);
        }


        $att['pwd'] = $request->input('password1');
        $user->update($att);
        return redirect()->route('clubs.parents_do');
    }

    public function get_telephone(Request $request,ClubStudent $club_student)
    {
        $att = $request->all();
        $club_student->update($att);
        return redirect()->route('clubs.parents_do');
    }

    public function show_club(Club $club)
    {
        if(empty(session('parents'))){
            return redirect()->route('clubs.semester_select');
        }

        $data = [
            'club'=>$club
        ];

        return view('clubs.show_club',$data);
    }

    public function sign_up(Club $club)
    {
        if(empty(session('parents'))){
            return redirect()->route('clubs.semester_select');
        }

        $user = ClubStudent::where('id',session('parents'))
            ->first();
        $club_register = ClubRegister::where('semester',$user->semester)
            ->where('club_id',$club->id)
            ->where('club_student_id',$user->id)
            ->first();
        $club_semester = ClubSemester::where('semester',$user->semester)
            ->first();

        $check_num = ClubRegister::where('semester',$user->semester)
            ->where('club_student_id',$user->id)
            ->count();

        $count_num = ClubRegister::where('semester',$user->semester)
            ->where('club_id',$club->id)
            ->count();

        //時間重疊就不能報名
        $tt = explode(';',$club->start_time);
        $check_registers = ClubRegister::where('semester',$user->semester)
            ->where('club_student_id',$user->id)
            ->get();
        foreach($check_registers as $check_register){
            $ss = explode(';',$check_register->club->start_time);
            foreach($ss as $k=>$v){
                $check_t = explode('-',$v);

                foreach($tt as $k2=>$v2){
                    $want_t = explode('-',$v2);
                    if($want_t[0] == $check_t[0]){
                        $beginTime1 = strtotime(date('Y-m-d').' '.$want_t[1]);
                        $endTime1 = strtotime(date('Y-m-d').' '.$want_t[2]);
                        $beginTime2 = strtotime(date('Y-m-d').' '.$check_t[1]);
                        $endTime2 = strtotime(date('Y-m-d').' '.$check_t[2]);

                        if($this->is_time_cross($beginTime1, $endTime1, $beginTime2, $endTime2)){
                            return back()->withErrors(['errors'=>[$club->name.' 此社團和已報名的社團時間衝突！']]);
                        }
                    }
                }

            }
        }

        //年級不對
        if(!in_array(substr($user->class_num,0,1),explode(',',$club->year_limit))){
            return back()->withErrors(['errors'=>[$club->name.' 此社團有年級限制，與你不符！']]);
        }


        if(empty($club_register) and $check_num < $club_semester->club_limit and $count_num < ($club->taking+$club->prepare)){
            $att['semester'] = $user->semester;
            $att['club_id'] = $club->id;
            $att['club_student_id'] = $user->id;
            ClubRegister::create($att);
        }

        return redirect()->route('clubs.parents_do');

    }

    public function sign_down($club_id)
    {
        if(empty(session('parents'))){
            return redirect()->route('clubs.semester_select');
        }

        $user = ClubStudent::where('id',session('parents'))
            ->first();

        ClubRegister::where('semester',$user->semester)
            ->where('club_id',$club_id)
            ->where('club_student_id',$user->id)
            ->delete();

        return redirect()->route('clubs.parents_do');

    }

    public function sign_show(Club $club)
    {
        if(empty(session('parents'))){
            return redirect()->route('clubs.semester_select');
        }

        $user = ClubStudent::where('id',session('parents'))
            ->first();

        $club_registers = ClubRegister::where('semester',$user->semester)
            ->where('club_id',$club->id)
            ->orderBy('created_at')
            ->get();
        $data = [
            'user'=>$user,
            'club'=>$club,
            'club_registers'=>$club_registers,
        ];

        return view('clubs.sign_show',$data);
    }

    public function report_situation($semester=null)
    {
        $club_semesters_array = ClubSemester::orderby('semester','DESC')->pluck('semester','semester')->toArray();
        if($semester == null){
            $s = ClubSemester::orderBy('semester','DESC')->first();
            $semester = $s->semester;
        }

        if($semester){
            $clubs = Club::where('semester',$semester)->get();
        }

        $data = [
            'club_semesters_array'=>$club_semesters_array,
            'semester'=>$semester,
            'clubs'=>$clubs,
        ];

        return view('clubs.report_situation',$data);
    }

    public function report_situation_download($semester)
    {
        $clubs = Club::where('semester',$semester)->get();
        $n = 1;
        foreach($clubs as $club){
            $club_registers = \App\ClubRegister::where('semester',$semester)
                ->where('club_id',$club->id)
                ->get();
            $taking = $club->taking;
            $prepare = $club->prepare;
            $i=1;
            $j=1;
            if(count($club_registers) < $club->people){
                $open = "不開班";
            }else{
                $open = "開班成功";
            }
            if(count($club_registers) >0){
                foreach($club_registers as $club_register){
                    if($i <= $taking) $order = "正取".$i;
                    if($i > $taking and $j <= $prepare){
                        $order = "備取".$j;
                        $j++;
                    }

                    $data[$n] =[
                        '社團'=>$club->name,
                        '班級座號'=>$club_register->user->class_num,
                        '姓名'=>$club_register->user->name,
                        '家長電話'=>$club_register->user->parents_telephone,
                        '錄取狀況'=>$order,
                        '開班狀態'=>$open,
                    ];
                    $i++;
                    $n++;
                }
            }else{
                $data[$n] =[
                    '社團'=>$club->name,
                    '班級座號'=>'',
                    '姓名'=>'',
                    '家長電話'=>'',
                    '錄取狀況'=>'',
                    '開班狀態'=>$open,
                ];
                $i++;
                $n++;
            }

        }


        $list = collect($data);

        return (new FastExcel($list))->download($semester.'_社團報名結果.xlsx');
    }

    public function report_register_delete(ClubRegister $club_register)
    {
        $admin = check_power('社團報名','A',auth()->user()->id);
        if($admin){
            $club_register->delete();
        }

        return redirect()->route('clubs.report_situation');
    }

    public function report_money($semester=null)
    {
        $club_semesters_array = ClubSemester::orderby('semester','DESC')->pluck('semester','semester')->toArray();
        if($semester == null){
            $s = ClubSemester::orderBy('semester','DESC')->first();
            $semester = $s->semester;
        }
        $clubs = [];
        $register_data = [];
        if($semester){
            $clubs = Club::where('semester',$semester)->get();
            $club_registers = ClubRegister::where('semester',$semester)->orderBy('club_student_id')->get();
            foreach($club_registers as $club_register){
                $register_data[$club_register->club->name][$club_register->user->id]['stud_no'] = $club_register->user->no;
                $register_data[$club_register->club->name][$club_register->user->id]['stud_num'] = substr($club_register->user->class_num,3,2);
                $register_data[$club_register->club->name][$club_register->user->id]['stud_name'] = $club_register->user->name;
                $register_data[$club_register->club->name][$club_register->user->id]['stud_year'] = substr($club_register->user->class_num,0,1);
                $register_data[$club_register->club->name][$club_register->user->id]['stud_class'] = substr($club_register->user->class_num,1,2);
                $register_data[$club_register->club->name][$club_register->user->id]['money'] = $club_register->club->money;

            }
        }

        $data = [
            'club_semesters_array'=>$club_semesters_array,
            'semester'=>$semester,
            'clubs'=>$clubs,
            'club_registers'=>$club_registers,
            'register_data'=>$register_data,
        ];

        return view('clubs.report_money',$data);
    }

    public function report_money_download($semester)
    {
        $club_semesters_array = ClubSemester::orderby('semester','DESC')->pluck('semester','semester')->toArray();

        $clubs = Club::where('semester',$semester)->get();
        $club_registers = ClubRegister::where('semester',$semester)->orderBy('club_student_id')->get();
        foreach($club_registers as $club_register){
            $register_data[$club_register->club->name][$club_register->user->id]['stud_no'] = $club_register->user->no;
            $register_data[$club_register->club->name][$club_register->user->id]['stud_num'] = substr($club_register->user->class_num,3,2);
            $register_data[$club_register->club->name][$club_register->user->id]['stud_name'] = $club_register->user->name;
            $register_data[$club_register->club->name][$club_register->user->id]['stud_year'] = substr($club_register->user->class_num,0,1);
            $register_data[$club_register->club->name][$club_register->user->id]['stud_class'] = substr($club_register->user->class_num,1,2);
            $register_data[$club_register->club->name][$club_register->user->id]['money'] = $club_register->club->money;

        }

        $check_id=0;
        $n=1;
        foreach($club_registers as $club_register){
            if($check_id != $club_register->user->id){
                $data[$n]=[
                    '學號'=>$register_data[$club_register->club->name][$club_register->user->id]['stud_no'],
                    '座號'=>(int)$register_data[$club_register->club->name][$club_register->user->id]['stud_num'],
                    '姓名'=>$register_data[$club_register->club->name][$club_register->user->id]['stud_name'],
                    '身分證字號'=>'',
                    '生日'=>'',
                    '年級'=>$register_data[$club_register->club->name][$club_register->user->id]['stud_year'],
                    '班別'=>(int)$register_data[$club_register->club->name][$club_register->user->id]['stud_class'],
                    '減免'=>'',
                ];
                foreach($clubs as $club){
                    if(isset($register_data[$club->name][$club_register->user->id]['money'])){
                        $data[$n][$club->name] = $register_data[$club->name][$club_register->user->id]['money'];
                    }else{
                        $data[$n][$club->name] = '';
                    }

                }
                $n++;
            }
            $check_id = $club_register->user->id;
        }

        $list = collect($data);

        return (new FastExcel($list))->download($semester.'_社團報名繳費單.xlsx');

    }



//檢查時間是否重疊
    public function is_time_cross($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = '') {
        $status = $beginTime2 - $beginTime1;
        if ($status > 0) {
            $status2 = $beginTime2 - $endTime1;
            if ($status2 >= 0) {
                return false;
            } else {
                return true;
            }
        } else {
            $status2 = $endTime2 - $beginTime1;
            if ($status2 > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

}
