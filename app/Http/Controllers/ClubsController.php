<?php

namespace App\Http\Controllers;

use App\Club;
use App\ClubSemester;
use App\ClubStudent;
use Illuminate\Http\Request;
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

        $data = [
            'user'=>$user,
            'clubs'=>$clubs,
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

}
