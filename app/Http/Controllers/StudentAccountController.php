<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setup;
use Rap2hpoutre\FastExcel\FastExcel;

class StudentAccountController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['學生帳號'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }
    public function index()
    {
        $admin = check_power('學生帳號', 'A', auth()->user()->id);        
        $school_code = school_code();
        $files = get_files(storage_path('app/privacy/' . $school_code . '/student_account'));  
        $all_students = []; // 建議先建立一個容器存結果    
        if(!empty($files)){
            $file = storage_path('app/privacy/'.$school_code.'/student_account/'.$files[0]);            
            $collection = (new FastExcel)->import($file);                        
            foreach ($collection as $line) {
                $stu_data['classnum'] = (isset($line['學生班級座號']))?$line['學生班級座號']:null;
                $stu_data['birthday'] = (isset($line['西元生日']))?$line['西元生日']:null;
                $stu_data['account'] = (isset($line['帳號']))?$line['帳號']:null;                      
                $all_students[] = $stu_data;          
            }
        }        
        $data = [   
            'admin'=>$admin,
            'school_code'=>$school_code,         
            'files'=>$files,
            'all_students'=>$all_students,
        ];
        return view('student_accounts.index',$data);
    }   
    
    public function upload(Request $request)
    {
        $admin = check_power('學生帳號', 'A', auth()->user()->id);        
        if (!$admin) {
            return response()->json(['status' => 'fail', 'message' => '沒有權限']);
        }
        $file = $request->file('file');
        if (!$file->isValid()) {
            return response()->json(['status' => 'fail', 'message' => '上傳失敗']);
        }
        $info = [
                'original_filename' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
        ];
        $school_code = school_code();
        $folder = 'privacy/' . $school_code . '/student_account/';
        //執行上傳檔案
        $file->storeAs($folder, $info['original_filename']);             
        return redirect()->route('student_account.index')->withErrors(['message' => ['上傳成功']]);        
    }
    
    public function delete($file)
    {
        $admin = check_power('學生帳號', 'A', auth()->user()->id);        
        if (!$admin) {
            return response()->json(['status' => 'fail', 'message' => '沒有權限']);
        }
        $school_code = school_code();
        $path = storage_path('app/privacy/' . $school_code . '/student_account/' . $file);
        if (file_exists($path)) {
            unlink($path);
            return redirect()->route('student_account.index')->withErrors(['message' => ['刪除成功']]);
        } else {
            return redirect()->route('student_account.index')->withErrors(['message' => ['檔案不存在']]);
        }
    }

    public function check()
    {        
        $key = rand(10000, 99999);
        session(['chaptcha' => $key]);
        $cht = array(0 => "零", 1 => "壹", 2 => "貳", 3 => "參", 4 => "肆", 5 => "伍", 6 => "陸", 7 => "柒", 8 => "捌", 9 => "玖");
        //$cht = array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",9=>"9");
        $cht_key = "";
        for ($i = 0; $i < 5; $i++) $cht_key .= $cht[substr($key, $i, 1)];

        session(['cht_chaptcha' => $cht_key]);

        
        $school_code = school_code();
        $files = get_files(storage_path('app/privacy/' . $school_code . '/student_account'));  
        $has_file = !empty($files);
        $data = [
            'has_file' => $has_file
        ];
        return view('student_accounts.check', $data);
    }

    public function do_check(Request $request)
    {
        // 1. 基本驗證
        $request->validate([
            'classnum' => 'required|string',
            'birthday' => 'required|digits:8', // 確保剛好是 8 位數字
        ], [
            'birthday.digits' => '生日格式必須為 8 碼數字。',
            'classnum.required' => '請輸入班級座號。'
        ]);

        $classnum = $request->input('classnum');
        $birthday = $request->input('birthday');

        if ($request->input('chaptcha') != session('chaptcha')) {
            if (!session('student_check_error')) {
                session(['student_check_error' => '1']);
            } else {
                $a = session('student_check_error');
                $a++;
                session(['student_check_error' => $a]);
            }
            return back()->withErrors(['error' => ['驗證碼錯誤！']]);
        }

        // 2. 查詢邏輯 (假設你有一個 Student 模型)
        $school_code = school_code();
        $files = get_files(storage_path('app/privacy/' . $school_code . '/student_account'));  
        $all_students = []; // 建議先建立一個容器存結果    
        if(!empty($files)){
            $file = storage_path('app/privacy/'.$school_code.'/student_account/'.$files[0]);            
            $collection = (new FastExcel)->import($file);                        
            foreach ($collection as $line) {
                $stu_data[$line['學生班級座號']][$line['西元生日']] = (isset($line['帳號']))?$line['帳號']:null;         
            }
        }  
         if (isset($stu_data[$classnum][$birthday])) {
                session(['student_check_error' => 0]); // 成功找到帳號，重置錯誤計數
                $account = $stu_data[$classnum][$birthday];
                return redirect()->route('student_account.check')->withErrors(['message' => ['找到帳號：'.$account]]);                                
          } else {
                return redirect()->route('student_account.check')->withErrors(['message' => ['查無此帳號，請確認班級座號和生日是否正確。']]);                
          }
        
    }
}
