<?php

namespace App\Http\Controllers;

use App\TeacherAbsent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAbsentController extends Controller
{
    public function index()
    {
        $semesters = DB::table('teacher_absents')
            ->select(DB::raw('semester'))
            ->groupBy('semester')
            ->pluck('semester','semester')
            ->toArray();

        $semester = get_date_semester(date('Y-m-d'));
        $teacher_absents = TeacherAbsent::where('user_id',auth()->user()->id)
            ->where('semester',$semester)
            ->orderBy('id','DESC')
            ->get();
        $abs_kinds = config('chcschool.abs_kinds');
        $class_dises = config('chcschool.class_dises');
        $user_name = get_user_name();
        $data = [
            'semesters'=>$semesters,
            'abs_kinds'=>$abs_kinds,
            'class_dises'=>$class_dises,
            'teacher_absents'=>$teacher_absents,
            'user_name'=>$user_name,
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

    public function store(Request $request)
    {
        if($request->input('day')==null and $request->input('hour')==null){
            return back()->withErrors(['errors'=>['請假日數及時數不得同為空值']]);
        }

        $teacher_absent = TeacherAbsent::create($request->all());

        //處理檔案上傳
        $school_code = school_code();
        $folder = 'privacy/'. $school_code .'/teacher_absent/'.$teacher_absent->id;
        if ($request->hasFile('note_file')) {
            $file = $request->file('note_file');

            $info = [
                'original_filename' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
            ];

            $file->storeAs($folder, $info['original_filename']);
        }

        return redirect()->route('teacher_absents.index');

    }
}
