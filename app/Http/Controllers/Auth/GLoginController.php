<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.glogin');
    }

    public function auth(Request $request)
    {
        $data = array("email"=>$request->input('username'),"password"=>$request->input('password'));
        $data_string = json_encode($data);
        $ch = curl_init('https://school.chc.edu.tw/api/auth');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        $obj = json_decode($result,true);

        if($obj['success']) {
            //非教職員，即跳開
            if($obj['kind'] != "教職員"){
                return back()->withErrors(['gsuite_error'=>['非教職員 GSuite 帳號']]);
            }

            if($obj['code'] != env('SCHOOL_CODE')){
                return back()->withErrors(['gsuite_error'=>['非本校教職員 GSuite 帳號']]);
            }

            //是否已有此帳號
            $user = User::where('username',$request->input('username'))
                ->where('login_type','gsuite')
                ->first();

            if(empty($user)){
                //無使用者，即建立使用者資料
                $att['username'] = $request->input('username');
                $att['name'] = $obj['name'];
                $att['password'] = bcrypt($request->input('password'));
                $att['code'] = $obj['code'];
                $att['school'] = $obj['school'];
                $att['kind'] = $obj['kind'];
                $att['title'] = $obj['title'];
                $att['login_type'] = "gsuite";
                $per_e_school = ['6','7','8'];
                if(in_array(substr($obj['code'],3,1),$per_e_school)){
                    $att['group_id'] = "1";
                }else{
                    $att['group_id'] = "2";
                }

                User::create($att);

            }else{
                //有此使用者，即更新使用者資料
                $att['name'] = $obj['name'];
                $att['password'] = bcrypt($request->input('password'));
                $att['code'] = $obj['code'];
                $att['school'] = $obj['school'];
                $att['kind'] = $obj['kind'];
                $att['title'] = $obj['title'];

                $user->update($att);
            }

            if(Auth::attempt(['username' => $request->input('username'),
                'password' => $request->input('password'),'disable' => null])){
                return redirect()->route('index');
            }else{
                return back()->withErrors(['gsuite_error'=>['被停權了？']]);
            }
        };

        return back()->withErrors(['gsuite_error'=>['GSuite認證錯誤']]);
    }
}
