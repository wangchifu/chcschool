<?php

namespace App\Http\Controllers;

use App\Fix;
use App\FixClass;
use App\Fun;
use App\Http\Requests\FixRequest;
use App\Setup;
use App\User;
use App\UserPower;
use App\ClubStudent;
use App\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use PHPExcel_IOFactory;
use PHPExcel;

class FixController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['報修系統'])) {
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
        $fixes = Fix::orderBy('id', 'DESC')
            ->paginate(20);
        $fix_admin = check_power('報修系統', 'A', auth()->user()->id);
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'fixes' => $fixes,
            'fix_admin' => $fix_admin,
            'types'=>$types,
        ];
        return view('fixes.index', $data);
    }
    public function search($situation)
    {
        $fixes = Fix::where('situation', $situation)
            ->orderBy('id', 'DESC')
            ->paginate(20);
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'situation' => $situation,
            'fixes' => $fixes,
            'types'=>$types,
        ];
        return view('fixes.search', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fix_classes = FixClass::where('disable',null)->orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'types'=>$types,
        ];
        return view('fixes.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $att['type'] = $request->input('type');
        $att['user_id'] = auth()->user()->id;
        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');
        $att['situation'] = "3";

        Fix::create($att);

        $att2['email'] = $request->input('email');
        $user = User::find(auth()->user()->id);
        $user->update($att2);

        //寄信給管理者
        $user_powers = UserPower::where('name', '報修系統')
            ->where('type', 'A')
            ->get();

        foreach ($user_powers as $user_power) {
            if (!empty($user_power->user->email)) {
                $email = $user_power->user->email;
                $subject = '學校網站中「' . auth()->user()->name . '」在「報修設備」寫了：' . $att['title'];
                $body = $att['content'];
                //send_mail($user_power->user->email, $subject, $body);
                Mail::raw($body, function ($body) use ($subject,$email){
                    $body->to($email)->subject($subject);
                });      
            }
        }

        foreach ($user_powers as $user_power) {
            if (!empty($user_power->user->line_key)) {
                $subject = '學校網站中「' . auth()->user()->name . '」在「報修設備」寫了：' . $att['title'];
                $body = $att['content'];
                $string = $subject."\n\n".$body;
                //line_notify($user_power->user->line_key,$string);
            }
            if (!empty($user_power->user->line_bot_token)) {
                $subject = '學校網站中「' . auth()->user()->name . '」在「報修設備」寫了：' . $att['title'];
                $body = $att['content'];
                $string = $subject."\n\n".$body;
                line_bot($user_power->user->line_user_id,$user_power->user->line_bot_token,$string);
            }
        }
        return redirect()->route('fixes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Fix $fix)
    {
        $fix_admin = check_power('報修系統', 'A', auth()->user()->id);
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'fix' => $fix,
            'fix_admin' => $fix_admin,
            'types'=>$types,
        ];
        return view('fixes.show', $data);
    }

    public function show_clean(Fix $fix)
    {
        $fix_admin = null;
        if(auth()->check()){
            $fix_admin = check_power('報修系統', 'A', auth()->user()->id);
        }
        
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'fix' => $fix,
            'fix_admin' => $fix_admin,
            'types'=>$types,
        ];
        return view('fixes.show_clean', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fix $fix)
    {
        $fix->update($request->all());

        $att['email'] = $request->input('email');
        $user = User::find(auth()->user()->id);
        $user->update($att);

        //寄信
        if (!empty($fix->user->email)) {
            $situation = ['2' => '處理中', '1' => '處理完畢'];

            $subject = '回覆「學校網站」中「報修設備」的問題';
            $body = "您問到：\r\n";
            $body .= $request->input('title') . "\r\n";
            $body .= "\r\n系統管理員回覆：\r\n";
            $body .= $situation[$request->input('situation')] . "\r\n";
            $body .= $request->input('reply');
            $body .= "\r\n-----這是系統信件，請勿回信-----";
            send_mail($fix->user->email, $subject, $body);
        }


        return redirect()->route('fixes.show', $fix->id);
    }

    public function update_clean(Request $request, Fix $fix)
    {
        $fix->update($request->all());

        $att['email'] = $request->input('email');
        $user = User::find(auth()->user()->id);
        $user->update($att);

        //寄信
        if (!empty($fix->user->email)) {
            $situation = ['2' => '處理中', '1' => '處理完畢'];

            $subject = '回覆「學校網站」中「報修設備」的問題';
            $body = "您問到：\r\n";
            $body .= $request->input('title') . "\r\n";
            $body .= "\r\n系統管理員回覆：\r\n";
            $body .= $situation[$request->input('situation')] . "\r\n";
            $body .= $request->input('reply');
            $body .= "\r\n-----這是系統信件，請勿回信-----";
            send_mail($fix->user->email, $subject, $body);
        }


        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function edit_class()
    {
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'fix_classes'=>$fix_classes,
            'types'=>$types,
        ];

        return view('fixes.edit_class',$data);
    }

    public function store_class(Request $request)
    {
        $att = $request->all();
        if(!isset($att['disable'])) $att['disable'] = null;
        FixClass::create($att);
        return redirect()->route('fixes.edit_class');
    }

    public function update_class(Request $request,FixClass $fix_class)
    {
        $att = $request->all();
        if(!isset($att['disable'])) $att['disable'] = null;
        $fix_class->update($att);
        return redirect()->route('fixes.edit_class');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fix $fix)
    {
        $fix->delete();
        return redirect()->route('fixes.index');
    }

    public function destroy_clean(Fix $fix)
    {
        $fix->delete();
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    function store_notify(Request $request){
        $att['line_key'] =  $request->input('line_key');
        $att['line_bot_token'] =  $request->input('line_bot_token');
        $att['line_user_id'] =  $request->input('line_user_id');
        $att['email'] =  $request->input('email');
        $user = User::where('id',auth()->user()->id)->first();
        $user->update($att);
        return redirect()->route('fixes.index');
    }

    public function stu_adm($semester=null)
    {
        if($semester == null){
            $semester = get_date_semester(date('Y-m-d'));
        }
        $class_num = StudentClass::where('semester', $semester)
            ->orderBy('student_year')
            ->orderBy('student_class')
            ->count();
        $club_student_num = ClubStudent::where('semester', $semester)
            ->where('disable', null)
            ->orderBy('class_num')
            ->count();        

        $data = [
            'club_student_num' => $club_student_num,            
            'semester' => $semester,            
            'class_num' => $class_num,
        ];
        return view('fixes.stu_adm', $data);
    }

    public function stu_adm_more($semester, $student_class_id = null)
    {
        $student_classes = StudentClass::where('semester', $semester)
            ->orderBy('student_year')
            ->orderBy('student_class')
            ->get();

        $student_class_id = ($student_class_id == null) ? $student_classes->first()->id : $student_class_id;

        $this_class = StudentClass::find($student_class_id);
        $sc = $this_class->student_year . sprintf("%02s", $this_class->student_class);

        $club_students = ClubStudent::where('semester', $semester)
            //->where('disable', null)
            ->where('class_num', 'like', $sc . '%')
            ->orderBy('class_num')
            ->get();

        $data = [
            'club_students' => $club_students,
            'semester' => $semester,
            'student_classes' => $student_classes,
            'this_class' => $this_class,
        ];
        return view('fixes.stu_adm_more', $data);
    }

    public function stu_import(Request $request, $semester)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $collection = (new FastExcel)->import($file);

            $studentsToInsert = [];
            $studentsToUpdate = [];
            $classTeachers = [];

            // 1. 一次取出該學期所有的學生完整資料 (載入記憶體做快速比對)
            $existingStudents = ClubStudent::where('semester', $semester)
                ->get()
                ->keyBy('no'); // 以學號做為 Key

            foreach ($collection as $line) {
                // 欄位檢查
                if (!isset($line['姓名'], $line['性別'], $line['年級(數字)'], $line['班序(數字)'], $line['生日(西元)'], $line['學號'], $line['座號'], $line['導師姓名'])) {
                    return back()->withErrors(['欄位有錯，請檢查 excel 檔']);
                }

                if (empty($line['姓名']) && empty($line['年級(數字)'])) {
                    break;
                }

                // 收集導師資料
                $classTeachers[$line['年級(數字)']][$line['班序(數字)']] = $line['導師姓名'];

                // 日期與班級編號處理
                $birthday = $line['生日(西元)']->format('Ymd');
                $classNum = $line['年級(數字)'] . sprintf("%02s", $line['班序(數字)']) . sprintf("%02s", $line['座號']);
                $studentNo = (string)$line['學號'];

                $data = [
                    'semester' => $semester,
                    'no' => $studentNo,
                    'name' => $line['姓名'],
                    'pwd' => $birthday,
                    'class_num' => $classNum,
                    'birthday' => $birthday,
                    'sex' => $line['性別'],
                    'updated_at' => now(),
                ];

                // 比對學生是否存在
                if (isset($existingStudents[$studentNo])) {
                    $old = $existingStudents[$studentNo];

                    // 2. 關鍵優化：只有資料真正「有變更」時才放入更新陣列
                    if (
                        $old->name !== $data['name'] ||
                        $old->pwd !== $data['pwd'] ||
                        $old->class_num !== $data['class_num'] ||
                        $old->birthday !== $data['birthday'] ||
                        $old->sex !== $data['sex']
                    ) {
                        $data['id'] = $old->id;
                        $studentsToUpdate[] = $data;
                    }
                } else {
                    // 不存在，放入新增陣列
                    $data['created_at'] = now();
                    $studentsToInsert[] = $data;
                }
            }

            // 3. 批量寫入新學生 (每 500 筆寫入一次)
            if (!empty($studentsToInsert)) {
                foreach (array_chunk($studentsToInsert, 500) as $chunk) {
                    ClubStudent::insert($chunk);
                }
            }

            // 4. 只更新資料有變動的學生
            if (!empty($studentsToUpdate)) {
                foreach ($studentsToUpdate as $studentData) {
                    ClubStudent::where('id', $studentData['id'])->update($studentData);
                }
            }

            // 5. 處理導師與班級資料 (同樣加上變更比對)
            if (!empty($classTeachers)) {
                $existingClasses = StudentClass::where('semester', $semester)
                    ->get()
                    ->keyBy(function ($item) {
                        return $item->student_year . '_' . $item->student_class;
                    });

                $classesToInsert = [];

                foreach ($classTeachers as $year => $classes) {
                    foreach ($classes as $classNum => $teacherName) {
                        $key = $year . '_' . $classNum;

                        if ($existingClasses->has($key)) {
                            $oldClass = $existingClasses[$key];
                            // 導師姓名有變動才更新 DB
                            if ($oldClass->user_names !== $teacherName || !is_null($oldClass->user_ids)) {
                                $oldClass->update([
                                    'user_names' => $teacherName,
                                    'user_ids' => null,
                                ]);
                            }
                        } else {
                            $classesToInsert[] = [
                                'semester' => $semester,
                                'student_year' => $year,
                                'student_class' => $classNum,
                                'user_names' => $teacherName,
                                'user_ids' => null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }

                if (!empty($classesToInsert)) {
                    StudentClass::insert($classesToInsert);
                }
            }
        }

        return redirect()->route('fixes.stu_adm', $semester);
    }    

    public function stu_backPWD(ClubStudent $club_student, $student_class_id)
    {
        $att['pwd'] = $club_student->birthday;
        $club_student->update($att);
        return redirect()->route('fixes.stu_adm_more', ['semester' => $club_student->semester, 'student_class_id' => $student_class_id]);
    }

    public function stu_edit(ClubStudent $club_student, StudentClass $student_class)
    {
        $sc = $student_class->student_year . sprintf("%02s", $student_class->student_class);
        $data = [
            'club_student' => $club_student,
            'semester' => $club_student->semester,
            'student_class_id' => $student_class->id,
            'sc' => $sc,
        ];
        return view('fixes.stu_edit', $data);
    }

    public function stu_update(Request $request, ClubStudent $club_student)
    {
        $att = $request->all();

        $club_student->update($att);
        return redirect()->route('fixes.stu_adm_more', ['semester' => $club_student->semester, 'student_class_id' => $att['student_class_id']]);
    }  
    
    public function stu_login()
    {        
        $data = [
            'semester'=>get_date_semester(date('Y-m-d')),
        ];
        return view('fixes.stu_login', $data);
    }

    public function stu_logout()
    {                
        session()->forget('stu_fix');
        return redirect()->route('fixes.stu_login');
    }

    public function stu_do_login(Request $request)
    {        
        $check = ClubStudent::where('class_num', $request->input('class_num'))
            ->where('semester', $request->input('semester'))
            ->where('disable', null)
            ->first();

        if (!$check) {            
            return back()->withErrors(['error' => ['查無此帳號！']]);
        } else {            
            if ($request->input('pwd') != $check->pwd) {
                return back()->withErrors(['error' => ['密碼錯誤！']]);
            } else {
                session(['stu_fix' => $check->class_num." ".$check->name]);
                return redirect()->route('fixes.stu_list', $request->input('class_id'));
            };
        }        
        $data = [
            'semester'=>get_date_semester(date('Y-m-d')),
        ];
        return view('fixes.stu_login', $data);
    }

    public function stu_list(){
        if(empty(session('stu_fix'))){
            return redirect()->route('fixes.stu_login');
        }else{
            $fixes = Fix::orderBy('id', 'DESC')
                ->paginate(20);            
            $fix_classes = FixClass::orderBy('order_by')->get();
            $types = [];
            foreach($fix_classes as $fix_class){
                $types[$fix_class->id] = $fix_class->name;
            } 
            $data = [
                'fixes' => $fixes,                
                'types'=>$types,
            ];            
            return view('fixes.stu_list', $data);
        }        
    }

    public function stu_create(){
        if(empty(session('stu_fix'))) return redirect()->route('fixes.stu_login');

        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [  
            'types'=>$types,          
        ];            
        return view('fixes.stu_create', $data);
    }

    public function stu_store(Request $request)
    {
        if(empty(session('stu_fix'))) return redirect()->route('fixes.stu_login');
        $att['type'] = $request->input('type');
        $att['user_id'] = 0;
        $att['title'] = session('stu_fix')." 申告：".$request->input('title');
        $att['content'] = $request->input('content');
        $att['situation'] = "3";

        Fix::create($att);        

        //寄信給管理者
        $user_powers = UserPower::where('name', '報修系統')
            ->where('type', 'A')
            ->get();

        foreach ($user_powers as $user_power) {
            if (!empty($user_power->user->email)) {
                $email = $user_power->user->email;
                $subject = '學校網站中「' . session('stu_fix') . '」在「報修設備」寫了：' . $att['title'];
                $body = $att['content'];                
                
                try {
                    Mail::raw($body, function ($message) use ($subject, $email) {
                        $message->to($email)->subject($subject);
                    });
                } catch (\Throwable $e) {                                                            
                    // 跳過這次失敗，繼續執行下一個人的寄信
                    continue;
                }
            }
        }        

        foreach ($user_powers as $user_power) {
            if (!empty($user_power->user->line_key)) {
                $subject = '學校網站中「' . session('stu_fix') . '」在「報修設備」寫了：' . $att['title'];
                $body = $att['content'];
                $string = $subject."\n\n".$body;
                //line_notify($user_power->user->line_key,$string);
            }
            if (!empty($user_power->user->line_bot_token)) {
                $subject = '學校網站中「' . session('stu_fix') . '」在「報修設備」寫了：' . $att['title'];
                $body = $att['content'];
                $string = $subject."\n\n".$body;
                line_bot($user_power->user->line_user_id,$user_power->user->line_bot_token,$string);
            }
        }
        return redirect()->route('fixes.stu_list');
    }

    public function stu_show(Fix $fix)
    {        
        if(empty(session('stu_fix'))) return redirect()->route('fixes.stu_login');
        
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'fix' => $fix,            
            'types'=>$types,
        ];
        return view('fixes.stu_show', $data);
    }
}
