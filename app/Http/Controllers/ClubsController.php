<?php

namespace App\Http\Controllers;

use App\Club;
use App\ClubBlack;
use App\ClubRegister;
use App\ClubSemester;
use App\ClubStudent;
use App\LunchSetup;
use App\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use PHPExcel_IOFactory;
use PHPExcel;

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
            'club_semesters' => $club_semesters
        ];
        return view('clubs.index', $data);
    }

    public function semester_create()
    {
        return view('clubs.create');
    }

    public function semester_store(Request $request)
    {
        $semester = $request->input('semester');
        $check = ClubSemester::where('semester', $semester)->first();

        if (!$check) {
            $att = $request->all();
            $att['start_date'] = $request->input('year_1') . '-' . sprintf("%02s", $request->input('month_1')) . '-' . sprintf("%02s", $request->input('day_1')) . '-' . sprintf("%02s", $request->input('hour_1')) . '-' . sprintf("%02s", $request->input('min_1'));
            $att['stop_date'] = $request->input('year_2') . '-' . sprintf("%02s", $request->input('month_2')) . '-' . sprintf("%02s", $request->input('day_2')) . '-' . sprintf("%02s", $request->input('hour_2')) . '-' . sprintf("%02s", $request->input('min_2'));
            $att['start_date2'] = $request->input('year2_1') . '-' . sprintf("%02s", $request->input('month2_1')) . '-' . sprintf("%02s", $request->input('day2_1')) . '-' . sprintf("%02s", $request->input('hour2_1')) . '-' . sprintf("%02s", $request->input('min2_1'));
            $att['stop_date2'] = $request->input('year2_2') . '-' . sprintf("%02s", $request->input('month2_2')) . '-' . sprintf("%02s", $request->input('day2_2')) . '-' . sprintf("%02s", $request->input('hour2_2')) . '-' . sprintf("%02s", $request->input('min2_2'));
            ClubSemester::create($att);
        } else {
            return back()->withErrors(['errors' => [$semester . '學期已經有設定了！']]);
        }

        return redirect()->route('clubs.index');
    }

    public function semester_delete($semester)
    {
        ClubSemester::where('semester', $semester)->delete();
        Club::where('semester', $semester)->delete();
        ClubStudent::where('semester', $semester)->delete();
        ClubRegister::where('semester', $semester)->delete();
        return redirect()->route('clubs.index');
    }

    public function semester_edit(ClubSemester $club_semester)
    {
        $data = [
            'club_semester' => $club_semester
        ];
        return view('clubs.edit', $data);
    }

    public function semester_update(Request $request, ClubSemester $club_semester)
    {
        $att = $request->all();
        $att['start_date'] = $request->input('year_1') . '-' . sprintf("%02s", $request->input('month_1')) . '-' . sprintf("%02s", $request->input('day_1')) . '-' . sprintf("%02s", $request->input('hour_1')) . '-' . sprintf("%02s", $request->input('min_1'));
        $att['stop_date'] = $request->input('year_2') . '-' . sprintf("%02s", $request->input('month_2')) . '-' . sprintf("%02s", $request->input('day_2')) . '-' . sprintf("%02s", $request->input('hour_2')) . '-' . sprintf("%02s", $request->input('min_2'));
        $att['start_date2'] = $request->input('year2_1') . '-' . sprintf("%02s", $request->input('month2_1')) . '-' . sprintf("%02s", $request->input('day2_1')) . '-' . sprintf("%02s", $request->input('hour2_1')) . '-' . sprintf("%02s", $request->input('min2_1'));
        $att['stop_date2'] = $request->input('year2_2') . '-' . sprintf("%02s", $request->input('month2_2')) . '-' . sprintf("%02s", $request->input('day2_2')) . '-' . sprintf("%02s", $request->input('hour2_2')) . '-' . sprintf("%02s", $request->input('min2_2'));
        $club_semester->update($att);
        return redirect()->route('clubs.index');
    }

    public function setup($semester = null)
    {
        $club_semesters_array = ClubSemester::orderby('semester', 'DESC')->pluck('semester', 'semester')->toArray();
        $clubs = [];
        if ($semester == null) {
            $s = ClubSemester::orderBy('semester', 'DESC')->first();
            if ($s) {
                $semester = $s->semester;
            } else {
                $semester = null;
            }
        }
        if ($semester) {
            $clubs1 = Club::where('semester', $semester)->where('class_id', '1')->orderBy('no')->get();
            $clubs2 = Club::where('semester', $semester)->where('class_id', '2')->orderBy('no')->get();
        }

        $data = [
            'club_semesters_array' => $club_semesters_array,
            'clubs1' => $clubs1,
            'clubs2' => $clubs2,
            'semester' => $semester,
        ];
        return view('clubs.setup', $data);
    }

    public function club_create($semester)
    {
        $club_classes = [
            '1' => '學生特色社團',
            '2' => '學生課後活動',
        ];
        $club_classes = [
            '1' => '學生特色社團',
            '2' => '學生課後活動',
        ];
        $data = [
            'semester' => $semester,
            'club_classes' => $club_classes,
        ];
        return view('clubs.club_create', $data);
    }

    public function club_store(Request $request)
    {
        $no_array = [
            '1' => 'A',
            '2' => 'B',
            '3' => 'C',
            '4' => 'D',
            '5' => 'E',
            '6' => 'F',
            '7' => 'G',
            '8' => 'H',
            '9' => 'I',
            '10' => 'J',
            '11' => 'K',
            '12' => 'L',
            '13' => 'M',
            '14' => 'N',
            '15' => 'O',
            '16' => 'P',
            '17' => 'Q',
            '18' => 'R',
            '19' => 'S',
            '20' => 'T',
            '21' => 'U',
            '22' => 'V',
            '23' => 'W',
            '24' => 'X',
            '25' => 'Y',
            '26' => 'Z',
            '27' => 'AA',
            '28' => 'AB',
            '29' => 'AC',
            '30' => 'AD',
            '31' => 'AE',
            '32' => 'AF',
            '33' => 'AG',
            '34' => 'AH',
            '35' => 'AI',
            '36' => 'AJ',
            '37' => 'AK',
            '38' => 'AL',
            '39' => 'AM',
            '40' => 'AN',
            '41' => 'AO',
            '42' => 'AP',
            '43' => 'AQ',
            '44' => 'AR',
            '45' => 'AS',
            '46' => 'AT',
            '47' => 'AU',
            '48' => 'AV',
            '49' => 'AW',
            '50' => 'AX',
        ];
        $semester = $request->input('semester');
        $name = $request->input('name');
        $class_id = $request->input('class_id');
        $count = Club::where('semester', $semester)
            ->where('class_id', $class_id)
            ->count();
        $no = $no_array[$count + 1];

        /**
        $check1 = Club::where('semester',$semester)
            ->where('no',$no)
            ->where('class_id',$class_id)
            ->first();
         */
        $check2 = Club::where('semester', $semester)
            ->where('no', $no)
            ->where('name', $name)
            ->where('class_id', $class_id)
            ->first();
        if ($check2) {
            return back()->withErrors(['errors' => [$request->input['name'] . ' 此類別中已經有設定此名稱的社團！']]);
        } else {
            $att = $request->all();
            $att['no'] = $no;
            $s1 = $att['start1_time1'] . "-" . $att['start1_time2'] . "-" . $att['start1_time3'];
            $s2 = $att['start2_time1'] . "-" . $att['start2_time2'] . "-" . $att['start2_time3'];
            $s3 = $att['start3_time1'] . "-" . $att['start3_time2'] . "-" . $att['start3_time3'];
            $s4 = $att['start4_time1'] . "-" . $att['start4_time2'] . "-" . $att['start4_time3'];
            $s5 = $att['start5_time1'] . "-" . $att['start5_time2'] . "-" . $att['start5_time3'];
            if ($att['start2_time1'] === "0") {
                $att['start_time'] = $s1;
            } else {
                $att['start_time'] = $s1 . ';' . $s2;
            }
            if ($att['start3_time1'] != "0") {
                $att['start_time'] = $att['start_time'] . ';' . $s3;
            }
            if ($att['start4_time1'] != "0") {
                $att['start_time'] = $att['start_time'] . ';' . $s4;
            }
            if ($att['start5_time1'] != "0") {
                $att['start_time'] = $att['start_time'] . ';' . $s5;
            }

            Club::create($att);

            return redirect()->route('clubs.setup', $semester);
        }
    }

    public function club_copy(Request $request)
    {
        $clubs = Club::where('semester', $request->input('semester1'))->get();

        foreach ($clubs as $club) {
            $att['no'] = $club->no;
            $att['class_id'] = $club->class_id;
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

        return redirect()->route('clubs.setup', $att['semester']);
    }

    public function club_edit(Club $club)
    {
        $club_classes = [
            '1' => '學生特色社團',
            '2' => '學生課後活動',
        ];
        $data = [
            'club' => $club,
            'club_classes' => $club_classes,
        ];

        return view('clubs.club_edit', $data);
    }

    public function club_update(Request $request, Club $club)
    {
        $att = $request->all();
        $s1 = $att['start1_time1'] . "-" . $att['start1_time2'] . "-" . $att['start1_time3'];
        $s2 = $att['start2_time1'] . "-" . $att['start2_time2'] . "-" . $att['start2_time3'];
        $s3 = $att['start3_time1'] . "-" . $att['start3_time2'] . "-" . $att['start3_time3'];
        $s4 = $att['start4_time1'] . "-" . $att['start4_time2'] . "-" . $att['start4_time3'];
        $s5 = $att['start5_time1'] . "-" . $att['start5_time2'] . "-" . $att['start5_time3'];
        if ($att['start2_time1'] === "0") {
            $att['start_time'] = $s1;
        } else {
            $att['start_time'] = $s1 . ';' . $s2;
        }
        if ($att['start3_time1'] != "0") {
            $att['start_time'] = $att['start_time'] . ';' . $s3;
        }
        if ($att['start4_time1'] != "0") {
            $att['start_time'] = $att['start_time'] . ';' . $s4;
        }
        if ($att['start5_time1'] != "0") {
            $att['start_time'] = $att['start_time'] . ';' . $s5;
        }
        $club->update($att);
        return redirect()->route('clubs.setup', $club->semester);
    }

    public function club_delete(Club $club)
    {
        $club->delete();
        return redirect()->route('clubs.setup', $club->semester);
    }

    public function stu_adm($semester)
    {
        $class_num = StudentClass::where('semester', $semester)
            ->orderBy('student_year')
            ->orderBy('student_class')
            ->count();
        $club_student_num = ClubStudent::where('semester', $semester)
            ->where('disable', null)
            ->orderBy('class_num')
            ->count();

        $club_blacks = ClubBlack::orderBy('semester')->get();

        $black_list = [];
        foreach ($club_blacks as $club_black) {
            $black_list[$club_black->semester][$club_black->no] = 1;
        }

        $data = [
            'club_student_num' => $club_student_num,
            'club_blacks' => $club_blacks,
            'semester' => $semester,
            'black_list' => $black_list,
            'class_num' => $class_num,
        ];
        return view('clubs.stu_adm', $data);
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
            ->where('disable', null)
            ->where('class_num', 'like', $sc . '%')
            ->orderBy('class_num')
            ->get();

        $data = [
            'club_students' => $club_students,
            'semester' => $semester,
            'student_classes' => $student_classes,
            'this_class' => $this_class,
        ];
        return view('clubs.stu_adm_more', $data);
    }

    public function stu_import(Request $request, $semester)
    {
        /** 
        if ($request->hasFile('file')) {

            ClubStudent::where('semester', $semester)->delete();
            ClubRegister::where('semester', $semester)->delete();

            $file = $request->file('file');
            $collection = (new FastExcel)->import($file);
            foreach ($collection as $k => $v) {
                $att['semester'] = $semester;
                $att['no'] = $v['學號'];
                $att['name'] = $v['姓名'];
                $att['pwd'] = date_format($v['生日(西元)'], 'Ymd');
                $att['class_num'] = $v['班級(數字)'] . sprintf("%02s", $v['座號']);
                $att['birthday'] = date_format($v['生日(西元)'], 'Ymd');
                ClubStudent::create($att);
            }
        }
         */

        //處理檔案上傳
        if ($request->hasFile('file')) {
            //ClubStudent::where('semester', $semester)->delete();
            //ClubRegister::where('semester', $semester)->delete();

            $file = $request->file('file');
            $collection = (new FastExcel)->import($file);
            //dd($collection);
            foreach ($collection as $line) {

                if (!isset($line['姓名']) or !isset($line['性別']) or !isset($line['年級(數字)']) or !isset($line['班序(數字)']) or !isset($line['班序(數字)']) or !isset($line['生日(西元)']) or !isset($line['學號']) or !isset($line['座號']) or !isset($line['導師姓名'])) {
                    return back()->withErrors(['欄位有錯，請檢查 excel 檔']);
                }

                if (empty($line['姓名']) and empty($line['年級(數字)'])) {
                    break;
                }
                $class_teacher[$line['年級(數字)']][$line['班序(數字)']] = $line['導師姓名'];

                $att['semester'] = $semester;
                $att['no'] = $line['學號'];
                $att['name'] = $line['姓名'];
                $b = explode('/', $line['生日(西元)']);
                $att['pwd'] = $b[0] . sprintf("%02s", $b[1]) . sprintf("%02s", $b[2]);
                $att['class_num'] = $line['年級(數字)'] . sprintf("%02s", $line['年級(數字)']) . sprintf("%02s", $line['座號']);
                $att['birthday'] = $att['pwd'];
                $att['sex'] = $line['性別'];

                $student = ClubStudent::where('semester', $att['semester'])
                    ->where('no', $att['no'])
                    ->first();
                if (empty($student)) {
                    ClubStudent::create($att);
                } else {
                    $student->update($att);
                }
            }
            foreach ($class_teacher as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    $att2['semester'] = $semester;
                    $att2['student_year'] = $k;
                    $att2['student_class'] = $k1;
                    $att2['user_names'] = $v1;

                    $student_class = StudentClass::where('semester', $att2['semester'])
                        ->where('student_year', $att2['student_year'])
                        ->where('student_class', $att2['student_class'])
                        ->first();
                    if (empty($student_class)) {
                        StudentClass::create($att2);
                    } else {
                        //避免先前拉過API 已經有導師了
                        $att2['user_ids'] = null;
                        $student_class->update($att2);
                    }
                }
            }
        }


        return redirect()->route('clubs.stu_adm', $semester);
    }

    public function stu_create($semester, StudentClass $student_class)
    {
        $sc = $student_class->student_year . sprintf("%02s", $student_class->student_class);
        $data = [
            'semester' => $semester,
            'student_class_id' => $student_class->id,
            'sc' => $sc,
        ];

        return view('clubs.stu_create', $data);
    }

    public function stu_store(Request $request, $semester)
    {
        $att = $request->all();

        $check = ClubStudent::where('no', $att['no'])->first();
        if ($check) {
            return back()->withErrors(['errors' => [$att['no'] . ' 此學號已經有設定了！']]);
        } else {
            $att['pwd'] = $att['birthday'];
            $att['semester'] = $semester;
            ClubStudent::create($att);
            return redirect()->route('clubs.stu_adm_more', ['semester' => $semester, 'student_class_id' => $att['student_class_id']]);
        }
    }

    public function stu_edit(ClubStudent $club_student, StudentClass $student_class)
    {
        $sc = $student_class->student_year . sprintf("%02s", $student_class->student_class);
        $data = [
            'club_student' => $club_student,
            'student_class_id' => $student_class->id,
            'sc' => $sc,
        ];
        return view('clubs.stu_edit', $data);
    }

    public function stu_update(Request $request, ClubStudent $club_student)
    {
        $att = $request->all();

        $club_student->update($att);
        return redirect()->route('clubs.stu_adm_more', ['semester' => $club_student->semester, 'student_class_id' => $att['student_class_id']]);
    }

    public function stu_delete(ClubStudent $club_student, $student_class_id)
    {
        $club_student->delete();
        return redirect()->route('clubs.stu_adm_more', ['semester' => $club_student->semester, 'student_class_id' => $student_class_id]);
    }

    public function stu_disable(ClubStudent $club_student, $student_class_id)
    {
        $att['disable'] = 1;
        $club_student->update($att);
        return redirect()->route('clubs.stu_adm_more', ['semester' => $club_student->semester, 'student_class_id' => $student_class_id]);
    }

    public function stu_backPWD(ClubStudent $club_student, $student_class_id)
    {
        $att['pwd'] = $club_student->birthday;
        $club_student->update($att);
        return redirect()->route('clubs.stu_adm_more', ['semester' => $club_student->semester, 'student_class_id' => $student_class_id]);
    }

    public function report()
    {
        return view('clubs.report');
    }

    public function semester_select()
    {
        session(['parents' => null]);
        //$semester = get_date_semester(date('Y-m-d'));
        //改列尚在報名中的
        $this_date = date('Y-m-d-H-i');
        $club_semesters = ClubSemester::where('stop_date', '>=', $this_date)->orWhere('stop_date2', '>=', $this_date)->orderBy('semester')->get();
        $data = [
            'club_semesters' => $club_semesters,
        ];
        return view('clubs.semester_select', $data);
    }

    public function parents_login($semester, $class_id)
    {
        $club_semester = ClubSemester::where('semester', $semester)->first();
        if ($class_id == '1') {
            if (date('YmdHi') >= str_replace('-', '', $club_semester->start_date) and date('YmdHi') <= str_replace('-', '', $club_semester->stop_date)) {
                $data = [
                    'semester' => $semester,
                    'class_id' => $class_id,
                ];
                return view('clubs.parents_login', $data);
            } else {
                return back();
            }
        }
        if ($class_id == '2') {
            if (date('YmdHi') >= str_replace('-', '', $club_semester->start_date2) and date('YmdHi') <= str_replace('-', '', $club_semester->stop_date2)) {
                $data = [
                    'semester' => $semester,
                    'class_id' => $class_id,
                ];
                return view('clubs.parents_login', $data);
            } else {
                return back();
            }
        }
    }

    public function do_login(Request $request)
    {
        if ($request->input('class_num')) {
            $check = ClubStudent::where('class_num', $request->input('class_num'))
                ->where('semester', $request->input('semester'))
                ->first();

            if (!$check) {
                return back()->withErrors(['error' => ['查無此帳號！']]);
            } else {
                if ($request->input('pwd') != $check->pwd) {
                    return back()->withErrors(['error' => ['密碼錯誤！']]);
                } else {
                    session(['parents' => $check->id]);
                    return redirect()->route('clubs.parents_do', $request->input('class_id'));
                };
            }
        }
    }

    public function parents_do($class_id)
    {
        if (empty(session('parents'))) {
            return redirect()->route('clubs.semester_select');
        }

        $user = ClubStudent::where('id', session('parents'))
            ->first();


        //檢查是否為黑名單
        $black = ClubBlack::where('semester', $user->semester)
            ->where('no', $user->no)
            ->where('class_id', $class_id)
            ->first();
        if ($class_id == 1) {
            $n = "1.學生特色社團";
        }
        if ($class_id == 2) {
            $n = "2.學生課後活動";
        }
        if (!empty($black)) {
            session(['parents' => null]);
            echo "<body onload=alert('你被處罰此學期無法報名" . $n . "')>";
            header("refresh:3;url=" . route('clubs.semester_select'));
            die();
        }


        $class_id = ($class_id) ? $class_id : 1;

        $clubs = Club::where('semester', $user->semester)
            ->where('class_id', $class_id)
            ->orderBy('no')
            ->get();

        $club_semester = ClubSemester::where('semester', $user->semester)
            ->first();


        //檢查是否非可報名時間
        if ($class_id == '1') {
            if (date('YmdHi') >= str_replace('-', '', $club_semester->start_date) and date('YmdHi') <= str_replace('-', '', $club_semester->stop_date)) {
            } else {
                echo "<body onload=alert('非報名時間')>";
                echo "<a href=2 onclick =history.back()>返回</a>";
                die();
            }
        }
        if ($class_id == '2') {
            if (date('YmdHi') >= str_replace('-', '', $club_semester->start_date2) and date('YmdHi') <= str_replace('-', '', $club_semester->stop_date2)) {
            } else {
                echo "<body onload=alert('非報名時間')>";
                echo "<a href=1 onclick =history.back()>返回</a>";
                die();
            }
        }


        $club_classes = [
            '1' => '1.學生特色社團 (' . $club_semester->start_date . '~' . $club_semester->stop_date . ')',
            '2' => '2.學生課後活動 (' . $club_semester->start_date2 . '~' . $club_semester->stop_date2 . ')',
        ];

        $data = [
            'user' => $user,
            'clubs' => $clubs,
            'club_semester' => $club_semester,
            'club_classes' => $club_classes,
            'class_id' => $class_id,
        ];
        return view('clubs.parents_do', $data);
    }

    public function parents_logout()
    {
        session(['parents' => null]);
        return redirect()->route('clubs.semester_select');
    }

    public function change_pwd($class_id)
    {
        if (empty(session('parents'))) {
            return redirect()->route('clubs.semester_select');
        }
        $user = ClubStudent::where('id', session('parents'))
            ->first();
        $data = [
            'user' => $user,
            'class_id' => $class_id,
        ];
        return view('clubs.change_pwd', $data);
    }

    public function change_pwd_do(Request $request)
    {
        $user = ClubStudent::where('id', session('parents'))
            ->first();

        if ($request->input('password0') != $user->pwd) {
            return back()->withErrors(['error' => ['舊密碼錯誤！你不是本人！？']]);
        }
        if ($request->input('password1') != $request->input('password2')) {
            return back()->withErrors(['error' => ['兩次新密碼不相同']]);
        }


        $att['pwd'] = $request->input('password1');
        $user->update($att);
        return redirect()->route('clubs.parents_do', $request->input('class_id'));
    }

    public function get_telephone(Request $request, ClubStudent $club_student)
    {
        $att = $request->all();
        $club_student->update($att);
        return redirect()->route('clubs.parents_do', $request->input('class_id'));
    }

    public function show_club(Club $club)
    {
        if (empty(session('parents'))) {
            return redirect()->route('clubs.semester_select');
        }

        $data = [
            'club' => $club
        ];

        return view('clubs.show_club', $data);
    }

    public function sign_up(Club $club)
    {
        if (empty(session('parents'))) {
            return redirect()->route('clubs.semester_select');
        }

        $user = ClubStudent::where('id', session('parents'))
            ->first();
        $club_register = ClubRegister::where('semester', $user->semester)
            ->where('club_id', $club->id)
            ->where('club_student_id', $user->id)
            ->first();
        $club_semester = ClubSemester::where('semester', $user->semester)
            ->first();

        if ($club->class_id == 1) {
            if (date('YmdHi') >= str_replace('-', '', $club_semester->start_date) and date('YmdHi') <= str_replace('-', '', $club_semester->stop_date)) {
            } else {
                return back();
            }
        }

        if ($club->class_id == 2) {
            if (date('YmdHi') >= str_replace('-', '', $club_semester->start_date2) and date('YmdHi') <= str_replace('-', '', $club_semester->stop_date2)) {
            } else {
                return back();
            }
        }


        $check_num = ClubRegister::where('semester', $user->semester)
            ->where('club_student_id', $user->id)
            ->where('class_id', $club->class_id)
            ->count();

        $count_num = ClubRegister::where('semester', $user->semester)
            ->where('club_id', $club->id)
            ->where('class_id', $club->class_id)
            ->count();

        //時間重疊就不能報名
        $tt = explode(';', $club->start_time);
        $check_registers = ClubRegister::where('semester', $user->semester)
            ->where('club_student_id', $user->id)
            ->get();
        foreach ($check_registers as $check_register) {
            $ss = explode(';', $check_register->club->start_time);
            foreach ($ss as $k => $v) {
                $check_t = explode('-', $v);

                foreach ($tt as $k2 => $v2) {
                    $want_t = explode('-', $v2);
                    if ($want_t[0] == $check_t[0]) {
                        $beginTime1 = strtotime(date('Y-m-d') . ' ' . $want_t[1]);
                        $endTime1 = strtotime(date('Y-m-d') . ' ' . $want_t[2]);
                        $beginTime2 = strtotime(date('Y-m-d') . ' ' . $check_t[1]);
                        $endTime2 = strtotime(date('Y-m-d') . ' ' . $check_t[2]);

                        if ($this->is_time_cross($beginTime1, $endTime1, $beginTime2, $endTime2)) {
                            return back()->withErrors(['errors' => [$club->name . ' 此社團和已報名的社團時間衝突！']]);
                        }
                    }
                }
            }
        }

        //年級不對
        if (!in_array(substr($user->class_num, 0, 1), explode(',', $club->year_limit))) {
            return back()->withErrors(['errors' => [$club->name . ' 此社團有年級限制，與你不符！']]);
        }


        if (empty($club_register) and $check_num < $club_semester->club_limit and $count_num < ($club->taking + $club->prepare)) {
            $att['semester'] = $user->semester;
            $att['club_id'] = $club->id;
            $att['club_student_id'] = $user->id;
            $att['class_id'] = $club->class_id;
            ClubRegister::create($att);
        }

        return redirect()->route('clubs.parents_do', $club->class_id);
    }

    public function sign_down($club_id)
    {
        if (empty(session('parents'))) {
            return redirect()->route('clubs.semester_select');
        }

        $user = ClubStudent::where('id', session('parents'))
            ->first();

        $club = Club::find($club_id);

        ClubRegister::where('semester', $user->semester)
            ->where('club_id', $club_id)
            ->where('club_student_id', $user->id)
            ->delete();

        return redirect()->route('clubs.parents_do', $club->class_id);
    }

    public function sign_show(Club $club, $class_id)
    {
        if (empty(session('parents'))) {
            return redirect()->route('clubs.semester_select');
        }

        $user = ClubStudent::where('id', session('parents'))
            ->first();

        $club_registers = ClubRegister::where('semester', $user->semester)
            ->where('club_id', $club->id)
            ->orderBy('created_at')
            ->get();
        $data = [
            'user' => $user,
            'club' => $club,
            'club_registers' => $club_registers,
            'class_id' => $class_id,
        ];

        return view('clubs.sign_show', $data);
    }

    public function report_situation($semester = null)
    {
        $club_semesters_array = ClubSemester::orderby('semester', 'DESC')->pluck('semester', 'semester')->toArray();
        if ($semester == null) {
            $s = ClubSemester::orderBy('semester', 'DESC')->first();
            if ($s) {
                $semester = $s->semester;
            } else {
                $semester = null;
            }
        }

        if ($semester) {
            $clubs1 = Club::where('semester', $semester)->where('class_id', '1')->orderBy('no')->get();
            $clubs2 = Club::where('semester', $semester)->where('class_id', '2')->orderBy('no')->get();
        } else {
            $clubs1 = [];
            $clubs2 = [];
        }

        $data = [
            'club_semesters_array' => $club_semesters_array,
            'semester' => $semester,
            'clubs1' => $clubs1,
            'clubs2' => $clubs2,
        ];

        return view('clubs.report_situation', $data);
    }

    public function report_situation_download($semester, $class_id)
    {
        $clubs = Club::where('semester', $semester)->where('class_id', $class_id)->get();
        $n = 1;
        foreach ($clubs as $club) {
            $club_registers = \App\ClubRegister::where('semester', $semester)
                ->where('club_id', $club->id)
                ->get();
            $taking = $club->taking;
            $prepare = $club->prepare;
            $i = 1;
            $j = 1;
            if (count($club_registers) < $club->people) {
                $open = "不開班";
            } else {
                $open = "開班成功";
            }
            if (count($club_registers) > 0) {
                foreach ($club_registers as $club_register) {
                    if ($i <= $taking) $order = "正取" . $i;
                    if ($i > $taking and $j <= $prepare) {
                        $order = "備取" . $j;
                        $j++;
                    }

                    $data[$n] = [
                        '社團' => $club->name,
                        '班級座號' => $club_register->user->class_num,
                        '姓名' => $club_register->user->name,
                        '家長電話' => $club_register->user->parents_telephone,
                        '錄取狀況' => $order,
                        '報名時間' => date('Y-m-d H:i:s', strtotime($club_register->created_at)),
                        '開班狀態' => $open,
                    ];
                    $i++;
                    $n++;
                }
            } else {
                $data[$n] = [
                    '社團' => $club->name,
                    '班級座號' => '',
                    '姓名' => '',
                    '家長電話' => '',
                    '錄取狀況' => '',
                    '報名時間' => '',
                    '開班狀態' => $open,
                ];
                $i++;
                $n++;
            }
        }


        $list = collect($data);

        return (new FastExcel($list))->download($semester . '_社團報名結果.xlsx');
    }

    public function report_register_delete(ClubRegister $club_register)
    {
        $admin = check_power('社團報名', 'A', auth()->user()->id);
        if ($admin) {
            $club_register->delete();
        }

        return redirect()->route('clubs.report_situation');
    }

    public function report_money($semester = null)
    {
        $club_semesters_array = ClubSemester::orderby('semester', 'DESC')->pluck('semester', 'semester')->toArray();
        if ($semester == null) {
            $s = ClubSemester::orderBy('semester', 'DESC')->first();
            if ($s) {
                $semester = $s->semester;
            } else {
                $semester = null;
            }
        }

        if ($semester) {
            $clubs1 = Club::where('semester', $semester)->where('class_id', '1')->orderBy('no')->get();
            $clubs2 = Club::where('semester', $semester)->where('class_id', '2')->orderBy('no')->get();

            $open_clubs1 = [];
            $open_clubs2 = [];
            $open_clubs_name1 = [];
            $open_clubs_name2 = [];
            foreach ($clubs1 as $club) {
                $check_people = ClubRegister::where('club_id', $club->id)->count();
                if ($check_people >= $club->people and $club->money != 0) {
                    $open_clubs1[] = $club->id;
                    $open_clubs_name1[$club->id] = $club->name;
                }

                //記錄有報名此社團的排名
                $cr1 = ClubRegister::where('club_id', $club->id)->orderBy('created_at')->get();
                $n = 1;
                foreach ($cr1 as $cr) {
                    $check_stu_order[$club->id][$cr->club_student_id] = $n;
                    $n++;
                }
            }

            foreach ($clubs2 as $club) {
                $check_people = ClubRegister::where('club_id', $club->id)->count();
                if ($check_people >= $club->people and $club->money != 0) {
                    $open_clubs2[] = $club->id;
                    $open_clubs_name2[$club->id] = $club->name;
                }

                $cr2 = ClubRegister::where('club_id', $club->id)->orderBy('created_at')->get();
                $n = 1;
                foreach ($cr2 as $cr) {
                    $check_stu_order[$club->id][$cr->club_student_id] = $n;
                    $n++;
                }
            }


            $club_registers1 = ClubRegister::where('semester', $semester)
                ->where('class_id', '1')
                ->whereIn('club_id', $open_clubs1)
                ->orderBy('club_student_id')->get();
            $club_registers2 = ClubRegister::where('semester', $semester)
                ->where('class_id', '2')
                ->whereIn('club_id', $open_clubs2)
                ->orderBy('club_student_id')->get();
            $register_data1 = [];
            $register_data2 = [];
            $students1 = [];
            $students2 = [];

            foreach ($club_registers1 as $club_register) {
                if (isset($check_stu_order[$club_register->club->id][$club_register->user->id])) {
                    if ($check_stu_order[$club_register->club->id][$club_register->user->id] <= $club_register->club->taking) {
                        $students1[$club_register->user->id]['no'] = $club_register->user->no;
                        $students1[$club_register->user->id]['num'] = substr($club_register->user->class_num, 3, 2);
                        $students1[$club_register->user->id]['name'] = $club_register->user->name;
                        $students1[$club_register->user->id]['year'] = substr($club_register->user->class_num, 0, 1);
                        $students1[$club_register->user->id]['class'] = substr($club_register->user->class_num, 1, 2);
                        $register_data1[$club_register->user->id][$club_register->club->id] = $club_register->club->money;
                    }
                }
            }
            foreach ($club_registers2 as $club_register) {
                if (isset($check_stu_order[$club_register->club->id][$club_register->user->id])) {
                    if ($check_stu_order[$club_register->club->id][$club_register->user->id] <= $club_register->club->taking) {
                        $students2[$club_register->user->id]['no'] = $club_register->user->no;
                        $students2[$club_register->user->id]['num'] = substr($club_register->user->class_num, 3, 2);
                        $students2[$club_register->user->id]['name'] = $club_register->user->name;
                        $students2[$club_register->user->id]['year'] = substr($club_register->user->class_num, 0, 1);
                        $students2[$club_register->user->id]['class'] = substr($club_register->user->class_num, 1, 2);
                        $register_data2[$club_register->user->id][$club_register->club->id] = $club_register->club->money;
                    }
                }
            }
        } else {
            $students1 = [];
            $students2 = [];
            $club_registers1 = [];
            $club_registers2 = [];
        }


        $data = [
            'club_semesters_array' => $club_semesters_array,
            'semester' => $semester,
            'open_clubs_name1' => $open_clubs_name1,
            'open_clubs_name2' => $open_clubs_name2,
            'club_registers1' => $club_registers1,
            'club_registers2' => $club_registers2,
            'register_data1' => $register_data1,
            'register_data2' => $register_data2,
            'students1' => $students1,
            'students2' => $students2,
        ];

        return view('clubs.report_money', $data);
    }

    public function report_money_download($semester, $class_id)
    {

        $clubs = Club::where('semester', $semester)->where('class_id', $class_id)->orderBy('no')->get();
        foreach ($clubs as $club) {
            $check_people = ClubRegister::where('club_id', $club->id)->count();
            if ($check_people >= $club->people and $club->money != 0) {
                $open_clubs[] = $club->id;
                $open_clubs_name[$club->id] = $club->name;
            }

            //記錄有報名此社團的排名
            $cr1 = ClubRegister::where('club_id', $club->id)->orderBy('created_at')->get();
            $n = 1;
            foreach ($cr1 as $cr) {
                $check_stu_order[$club->id][$cr->club_student_id] = $n;
                $n++;
            }
        }



        $club_registers = ClubRegister::where('semester', $semester)
            ->where('class_id', $class_id)
            ->whereIn('club_id', $open_clubs)
            ->orderBy('club_student_id')->get();


        foreach ($club_registers as $club_register) {
            if (isset($check_stu_order[$club_register->club->id][$club_register->user->id])) {
                if ($check_stu_order[$club_register->club->id][$club_register->user->id] <= $club_register->club->taking) {
                    $students[$club_register->user->id]['no'] = $club_register->user->no;
                    $students[$club_register->user->id]['num'] = substr($club_register->user->class_num, 3, 2);
                    $students[$club_register->user->id]['name'] = $club_register->user->name;
                    $students[$club_register->user->id]['year'] = substr($club_register->user->class_num, 0, 1);
                    $students[$club_register->user->id]['class'] = substr($club_register->user->class_num, 1, 2);
                    $register_data[$club_register->user->id][$club_register->club->id] = $club_register->club->money;
                }
            }
        }

        $n = 1;
        foreach ($students as $k => $v) {
            $data[$n] = [
                '學號' => $v['no'],
                '座號' => (int)$v['num'],
                '姓名' => $v['name'],
                '身分證字號' => '',
                '生日' => '',
                '年級' => $v['year'],
                '班別' => (int)$v['class'],
                '減免' => '',
            ];
            foreach ($open_clubs_name as $k2 => $v2) {
                if (isset($register_data[$k][$k2])) {
                    $data[$n][$v2] = $register_data[$k][$k2];
                } else {
                    $data[$n][$v2] = '';
                }
            }
            $n++;
        }

        $list = collect($data);

        if ($class_id == 1) $name = "學生特色社團";
        if ($class_id == 2) $name = "學生課後活動";
        return (new FastExcel($list))->download($semester . '_' . $name . '繳費單.xlsx');
    }

    public function report_money2_print($semester, $class_id)
    {
        //取長官印章圖片
        $lunch_setup = LunchSetup::where('semester', $semester)->first();

        $clubs = Club::where('semester', $semester)->where('class_id', $class_id)->orderBy('no')->get();
        foreach ($clubs as $club) {
            $check_people = ClubRegister::where('club_id', $club->id)->count();
            if ($check_people >= $club->people and $club->money != 0) {
                $open_clubs[] = $club->id;
                $open_clubs_name[$club->id] = $club->name;
            }
            //記錄有報名此社團的排名
            $cr1 = ClubRegister::where('club_id', $club->id)->orderBy('created_at')->get();
            $n = 1;
            foreach ($cr1 as $cr) {
                $check_stu_order[$club->id][$cr->club_student_id] = $n;
                $n++;
            }
        }

        $club_registers = ClubRegister::where('semester', $semester)
            ->where('class_id', $class_id)
            ->whereIn('club_id', $open_clubs)
            ->orderBy('club_student_id')->get();

        foreach ($club_registers as $club_register) {
            if (isset($check_stu_order[$club_register->club->id][$club_register->user->id])) {
                if ($check_stu_order[$club_register->club->id][$club_register->user->id] <= $club_register->club->taking) {
                    $students[$club_register->user->id]['no'] = $club_register->user->no;
                    $students[$club_register->user->id]['num'] = substr($club_register->user->class_num, 3, 2);
                    $students[$club_register->user->id]['name'] = $club_register->user->name;
                    $students[$club_register->user->id]['year'] = substr($club_register->user->class_num, 0, 1);
                    $students[$club_register->user->id]['class'] = substr($club_register->user->class_num, 1, 2);
                    $register_data[$club_register->club->id][$club_register->user->id] = $club_register->club->money;
                }
            }
        }

        $data = [
            'lunch_setup' => $lunch_setup,
            'open_clubs' => $open_clubs,
            'open_clubs_name' => $open_clubs_name,
            'students' => $students,
            'register_data' => $register_data,
            'semester' => $semester,
        ];
        return view('clubs.report_money2_print', $data);
    }

    public function report_money_download2($semester, $class_id)
    {
        $clubs = Club::where('semester', $semester)->where('class_id', $class_id)->orderBy('no')->get();
        foreach ($clubs as $club) {
            $check_people = ClubRegister::where('club_id', $club->id)->count();
            if ($check_people >= $club->people and $club->money != 0) {
                $open_clubs[] = $club->id;
                $open_clubs_name[$club->id] = $club->name;
            }

            //記錄有報名此社團的排名
            $cr1 = ClubRegister::where('club_id', $club->id)->orderBy('created_at')->get();
            $n = 1;
            foreach ($cr1 as $cr) {
                $check_stu_order[$club->id][$cr->club_student_id] = $n;
                $n++;
            }
        }



        $club_registers = ClubRegister::where('semester', $semester)
            ->where('class_id', $class_id)
            ->whereIn('club_id', $open_clubs)
            ->orderBy('club_student_id')->get();


        foreach ($club_registers as $club_register) {
            if (isset($check_stu_order[$club_register->club->id][$club_register->user->id])) {
                if ($check_stu_order[$club_register->club->id][$club_register->user->id] <= $club_register->club->taking) {
                    $students[$club_register->user->id]['no'] = $club_register->user->no;
                    $students[$club_register->user->id]['num'] = substr($club_register->user->class_num, 3, 2);
                    $students[$club_register->user->id]['name'] = $club_register->user->name;
                    $students[$club_register->user->id]['year'] = substr($club_register->user->class_num, 0, 1);
                    $students[$club_register->user->id]['class'] = substr($club_register->user->class_num, 1, 2);
                    $register_data[$club_register->user->id][$club_register->club->id] = $club_register->club->money;
                }
            }
        }
        /** 
        $n = 1;
        foreach ($students as $k => $v) {
            $data[$n] = [
                '學號' => $v['no'],
                '座號' => (int)$v['num'],
                '姓名' => $v['name'],
                '身分證字號' => '',
                '生日' => '',
                '年級' => $v['year'],
                '班別' => (int)$v['class'],
                '減免' => '',
            ];
            foreach ($open_clubs_name as $k2 => $v2) {
                if (isset($register_data[$k][$k2])) {
                    $data[$n][$v2] = $register_data[$k][$k2];
                } else {
                    $data[$n][$v2] = '';
                }
            }
            $n++;
        }

        $list = collect($data);

        if ($class_id == 1) $name = "學生特色社團";
        if ($class_id == 2) $name = "學生課後活動";
        return (new FastExcel($list))->download($semester . '_' . $name . '繳費單.xlsx');
         */
        $eng = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        if ($class_id == 1) $name = "學生特色社團";
        if ($class_id == 2) $name = "學生課後活動";
        $objExcel = new PHPExcel();
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');

        $objActSheet = $objExcel->getActiveSheet(0);
        $objActSheet->setTitle($name . '收費名單'); //设置excel的标题
        $objActSheet->setCellValue('A1', '學號');
        $objActSheet->setCellValue('B1', '姓名');
        $objActSheet->setCellValue('C1', '生日');
        $objActSheet->setCellValue('D1', '座號');
        $objActSheet->setCellValue('E1', '減免');
        $objActSheet->setCellValue('F1', '年級');
        $objActSheet->setCellValue('G1', '班別');
        $objActSheet->setCellValue('H1', '身分證字號');
        $n = 8;
        foreach ($open_clubs_name as $k => $v) {
            $objActSheet->setCellValue($eng[$n] . '1', $v);
            $n++;
        }

        $baseRow = 2; //数据从N-1行开始往下输出 这里是避免头信息被覆盖

        foreach ($students as $k => $v) {
            $objExcel->getActiveSheet()->setCellValue('A' . $baseRow, $v['no']);
            $v['name'] = mb_convert_encoding(mb_convert_encoding($v['name'], 'big5', 'utf-8'), 'utf-8', 'big5');
            $objExcel->getActiveSheet()->setCellValue('B' . $baseRow, $v['name']);
            $objExcel->getActiveSheet()->setCellValue('C' . $baseRow, '');
            $objExcel->getActiveSheet()->setCellValue('D' . $baseRow, (string)(int)$v['num']);
            $objExcel->getActiveSheet()->setCellValue('E' . $baseRow, '');
            $objExcel->getActiveSheet()->setCellValue('F' . $baseRow, $v['year']);
            $objExcel->getActiveSheet()->setCellValue('G' . $baseRow, (string)(int)$v['class']);
            $objExcel->getActiveSheet()->setCellValue('H' . $baseRow, '');

            $n = 8;
            foreach ($open_clubs_name as $k2 => $v2) {
                if (isset($register_data[$k][$k2])) {
                    $objExcel->getActiveSheet()->setCellValue($eng[$n] . $baseRow, $register_data[$k][$k2]);
                    $data[$n][$v2] = $register_data[$k][$k2];
                } else {
                    $objExcel->getActiveSheet()->setCellValue($eng[$n] . $baseRow, '');
                    $data[$n][$v2] = '';
                }
                $n++;
            }
            $baseRow++;
        }


        $objExcel->setActiveSheetIndex(0);
        //4、输出
        $objExcel->setActiveSheetIndex();
        header('Content-Type: applicationnd.ms-excel');
        header("Content-Disposition: attachment;filename={$semester}_{$name}收費匯入單.xls");
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }



    //檢查時間是否重疊
    public function is_time_cross($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = '')
    {
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

    public function black(Request  $request)
    {
        $att = $request->all();

        $check = ClubBlack::where('no', $att['no'])
            ->where('semester', $att['semester'])
            ->where('class_id', $att['class_id'])
            ->first();
        if (!empty($check)) return back()->withErrors(['errors' => [$att['semester'] . '學期 學號' . $att['no'] . ' 此生已經有設定了！']]);

        $check2 = ClubStudent::where('no', $att['no'])->first();
        if (empty($check2)) return back()->withErrors(['errors' => ['學號' . $att['no'] . ' 查無此學生！']]);

        ClubBlack::create($att);
        return redirect()->route('clubs.stu_adm', $att['back_semester']);
    }

    public function destroy_black($semester, ClubBlack $club_black)
    {
        $club_black->delete();
        return redirect()->route('clubs.stu_adm', $semester);
    }
}
