<?php

namespace App\Http\Controllers;

use App\TeacherAbsent;
use App\User;
use App\UserPower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAbsentController extends Controller
{
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

        $teacher_absent->update($att);

        return redirect()->route('teacher_absents.index');

    }

    public function update(Request $request,TeacherAbsent $teacher_absent)
    {
        $teacher_absent->update($request->all());
        return redirect()->route('teacher_absents.index');
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
        return view('teacher_absents.deputy',$data);
    }


    public function check($type,TeacherAbsent $teacher_absent)
    {
        if($type=="deputy" and $teacher_absent->deputy_user_id == auth()->user()->id){
            $att['deputy_date'] = date('Y-m-d H:i:s');
            $teacher_absent->update($att);

            return redirect()->route('teacher_absents.deputy');
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


        $semesters = DB::table('teacher_absents')
            ->select(DB::raw('semester'))
            ->groupBy('semester')
            ->orderBy('semester')
            ->pluck('semester','semester')
            ->toArray();

        $semester = ($select_semester)?$select_semester:get_date_semester(date('Y-m-d'));

        $teacher_absents = TeacherAbsent::where('semester',$semester)
            ->where('deputy_date','<>',null)
            ->orderBy('check1_date')
            ->orderBy('check2_date')
            ->orderBy('check3_date')
            ->orderBy('check4_date')
            ->orderBy('id','DESC')
            ->get();
        $abs_kinds = config('chcschool.abs_kinds');
        $class_dises = config('chcschool.class_dises');
        $user_name = get_user_name();
        $school_code = school_code();
        $data = [
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
}
