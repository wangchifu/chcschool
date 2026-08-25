<?php

namespace App\Http\Controllers;

use App\Club;
use App\ClubStudent;
use App\LunchFactory;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchPlace;
use App\LunchSetup;
use App\LunchStuDate;
use App\LunchTeaDate;
use App\StudentClass;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class LunchSetupController extends Controller
{
    public function index()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        $lunch_setups = LunchSetup::orderBy('semester', 'DESC')
            ->paginate(10);
        $places = LunchPlace::orderBy('disable')->get();
        $factories = LunchFactory::orderBy('disable')->get();

        $data = [
            'admin' => $admin,
            'lunch_setups' => $lunch_setups,
            'places' => $places,
            'factories' => $factories,
        ];

        return view('lunch_setups.index', $data);
    }

    public function create()
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);
        $data = [
            'admin' => $admin,
        ];
        return view('lunch_setups.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester' => 'required|numeric',
            'die_line' => 'required|numeric',
            'all_rece_name' => 'required',
            'all_rece_date' => 'required|date',
            'all_rece_no' => 'required',
            'all_rece_num' => 'required|numeric',
            'teacher_money' => 'required|numeric',
            'file1' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file2' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file3' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file4' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $att['semester'] = $request->input('semester');

        //不得重復同學期
        $check_semester = LunchSetup::where('semester', $att['semester'])->first();
        if ($check_semester) {
            return back()->withErrors(['errors' => ['此學期已建置！']]);
        }

        $eat_styles = $request->input('eat_styles');

        $eat_str = "";
        foreach ($eat_styles as $eat_style) {
            $eat_str .= $eat_style . ",";
        }
        $eat_str = substr($eat_str, 0, -1);
        $att['eat_styles'] = $eat_str;

        $att['die_line'] = $request->input('die_line');
        if ($request->input('teacher_open')) {
            $att['teacher_open'] = 1;
        }
        if ($request->input('disable')) {
            $att['disable'] = 1;
        }
        $att['all_rece_name'] = $request->input('all_rece_name');
        $att['all_rece_date'] = $request->input('all_rece_date');
        $att['all_rece_no'] = $request->input('all_rece_no');
        $att['all_rece_num'] = $request->input('all_rece_num');
        $att['teacher_money'] = $request->input('teacher_money');

        $lunch_setup = LunchSetup::create($att);

        $school_code = school_code();

        if ($request->hasFile('file1')) {
            $file = $request->file('file1');

            $file->storeAs('privacy/' . $school_code . '/lunches/' . $lunch_setup->id, 'seal1.png');
        }
        if ($request->hasFile('file2')) {
            $file = $request->file('file2');

            $file->storeAs('privacy/' . $school_code . '/lunches/' . $lunch_setup->id, 'seal2.png');
        }
        if ($request->hasFile('file3')) {
            $file = $request->file('file3');

            $file->storeAs('privacy/' . $school_code . '/lunches/' . $lunch_setup->id, 'seal3.png');
        }
        if ($request->hasFile('file4')) {
            $file = $request->file('file4');

            $file->storeAs('privacy/' . $school_code . '/lunches/' . $lunch_setup->id, 'seal4.png');
        }

        return redirect()->route('lunch_setups.index');
    }

    public function edit(LunchSetup $lunch_setup)
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

        $data = [
            'lunch_setup' => $lunch_setup,
            'admin' => $admin,
        ];

        return view('lunch_setups.edit', $data);
    }

    public function update(Request $request, LunchSetup $lunch_setup)
    {
        $request->validate([
            'semester' => 'required|numeric',
            'die_line' => 'required|numeric',
            'all_rece_name' => 'required',
            'all_rece_date' => 'required|date',
            'all_rece_no' => 'required',
            'all_rece_num' => 'required|numeric',
            'teacher_money' => 'required|numeric',
            'file1' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file2' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file3' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file4' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $att['semester'] = $request->input('semester');
        $eat_styles = $request->input('eat_styles');

        $eat_str = "";
        foreach ($eat_styles as $eat_style) {
            $eat_str .= $eat_style . ",";
        }
        $eat_str = substr($eat_str, 0, -1);
        $att['eat_styles'] = $eat_str;
        $att['die_line'] = $request->input('die_line');
        if ($request->input('teacher_open')) {
            $att['teacher_open'] = 1;
        } else {
            $att['teacher_open'] = null;
        }
        if ($request->input('disable')) {
            $att['disable'] = 1;
        } else {
            $att['disable'] = null;
        }
        $att['all_rece_name'] = $request->input('all_rece_name');
        $att['all_rece_date'] = $request->input('all_rece_date');
        $att['all_rece_no'] = $request->input('all_rece_no');
        $att['all_rece_num'] = $request->input('all_rece_num');
        $att['teacher_money'] = $request->input('teacher_money');

        $lunch_setup->update($att);

        $school_code = school_code();

        if ($request->hasFile('file1')) {
            $file = $request->file('file1');

            $file->storeAs('privacy/' . $school_code . '/lunches/' . $lunch_setup->id, 'seal1.png');
        }
        if ($request->hasFile('file2')) {
            $file = $request->file('file2');

            $file->storeAs('privacy/' . $school_code . '/lunches/' . $lunch_setup->id, 'seal2.png');
        }
        if ($request->hasFile('file3')) {
            $file = $request->file('file3');

            $file->storeAs('privacy/' . $school_code . '/lunches/' . $lunch_setup->id, 'seal3.png');
        }
        if ($request->hasFile('file4')) {
            $file = $request->file('file4');

            $file->storeAs('privacy/' . $school_code . '/lunches/' . $lunch_setup->id, 'seal4.png');
        }

        return redirect()->route('lunch_setups.index');
    }

    public function destroy(LunchSetup $lunch_setup)
    {
        $school_code = school_code();
        delete_dir(storage_path('app/privacy/' . $school_code . '/lunches/' . $lunch_setup->id));

        LunchOrder::where('semester', $lunch_setup->semester)->delete();
        LunchOrderDate::where('semester', $lunch_setup->semester)->delete();
        LunchTeaDate::where('semester', $lunch_setup->semester)->delete();
        LunchStuDate::where('semester', $lunch_setup->semester)->delete();

        $lunch_setup->delete();
        return redirect()->route('lunch_setups.index');
    }

    public function del_file($path, $id)
    {
        $school_code = school_code();
        $path = str_replace('&', '/', $path);
        $path = storage_path('app/privacy/' . $school_code . '/' . $path);
        if (file_exists($path)) {
            unlink($path);
        }
        return redirect()->route('lunch_setups.edit', $id);
    }

    public function place_add(Request $request)
    {
        $att['name'] = $request->input('name');
        $att['disable'] = ($request->input('disable')) ? 1 : null;
        LunchPlace::create($att);
        return redirect()->route('lunch_setups.index');
    }

    public function place_update(Request $request, LunchPlace $lunch_place)
    {
        $att['name'] = $request->input('name');
        $att['disable'] = ($request->input('disable')) ? 1 : null;
        $lunch_place->update($att);
        return redirect()->route('lunch_setups.index');
    }

    public function factory_add(Request $request)
    {
        $att['name'] = $request->input('name');
        $att['fid'] = $request->input('fid');
        $att['fpwd'] = $request->input('fpwd');
        $att['disable'] = ($request->input('disable')) ? 1 : null;
        LunchFactory::create($att);
        return redirect()->route('lunch_setups.index');
    }

    public function factory_update(Request $request, LunchFactory $lunch_factory)
    {
        $att['name'] = $request->input('name');
        $att['fid'] = $request->input('fid');
        $att['fpwd'] = $request->input('fpwd');
        $att['disable'] = ($request->input('disable')) ? 1 : null;
        $lunch_factory->update($att);
        return redirect()->route('lunch_setups.index');
    }

    public function stu_store(Request $request)
    {
        $semester = $request->input('semester');

        // 處理檔案上傳
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

            // 3. 批量寫入新增學生 (每 500 筆一包)
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

            // 5. 處理導師與班級資料 (加上變更比對)
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

        return redirect()->back();
    }

    public function stu_more($semester, $student_class_id = null)
    {
        $admin = check_power('午餐系統', 'A', auth()->user()->id);

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
            'admin' => $admin,
            'club_students' => $club_students,
            'semester' => $semester,
            'student_classes' => $student_classes,
            'this_class' => $this_class,
        ];
        return view('lunch_setups.stu_more', $data);
    }
}
