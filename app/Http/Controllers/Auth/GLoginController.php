<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GLoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        return view('auth.glogin');
    }

    public function auth(Request $request)
    {
        if(session('login_error') >= 3){
            return view('errors.500');
        }

        if($request->input('chaptcha') != session('chaptcha')){
            if(!session('login_error')){
                session(['login_error' => 1]);
            }else{
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }

            return back()->withErrors(['gsuite_error'=>['驗證碼錯誤！']]);
        }

        $data = array("email"=>$request->input('username'),"password"=>$request->input('password'));
        $data_string = json_encode($data);
        $ch = curl_init('https://school.chc.edu.tw/api/auth');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
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

            $database = config('app.database');
            if(isset($_SERVER['HTTP_HOST'])){
                $d = $database[$_SERVER['HTTP_HOST']];
            }else{
                $d = env('DB_DATABASE');
            }

            $code = $obj['code'];
            $school = $obj['school'];

            if($obj['code'] != substr($d,1,6)){
                $check_code = 0;
                foreach($obj['schools'] as $v){
                    if($v['code'] == substr($d,1,6)){
                        $check_code =1;
                        $code = $v['code'];
                        $school = $v['name'];
                    }
                }
                if($check_code == 0){
                    return back()->withErrors(['gsuite_error'=>['非本校教職員 GSuite 帳號']]);
                }

            }



            //是否已有此帳號
            //$username = str_replace('@chc.edu.tw','',$request->input('username'));
            $u = explode('@',$request->input('username'));
            $username = $u[0];
            $user = User::where('username',$username)
                ->where('login_type','gsuite')
                ->first();

            if(empty($user)){
                //無使用者，即建立使用者資料
                $att['username'] = $username;
                $att['name'] = $obj['name'];
                $att['password'] = bcrypt($request->input('password'));
                $att['code'] = $code;
                $att['school'] = $school;
                $att['kind'] = $obj['kind'];
                $att['title'] = $obj['title'];
                $att['login_type'] = "gsuite";

                User::create($att);

            }else{
                //有此使用者，即更新使用者資料
                $att['name'] = $obj['name'];
                $att['password'] = bcrypt($request->input('password'));
                $att['code'] = $code;
                $att['school'] = $school;
                $att['kind'] = $obj['kind'];
                $att['title'] = $obj['title'];
                $att['password'] = bcrypt($request->input('password'));

                $user->update($att);
            }

            if(Auth::attempt(['username' => $username,
                'password' => $request->input('password'),'login_type'=>'gsuite','disable' => null])){
		//return redirect()->route('index');
		if(empty($request->session()->get('url.intended'))){
		  return redirect()->route('index');
		}else{
		  return redirect($request->session()->get('url.intended'));
		}
            }else{
                return back()->withErrors(['gsuite_error'=>['被停權了？']]);
            }
        }else{
            if(!session('login_error')){
                session(['login_error' => 1]);
            }else{
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }

            return back()->withErrors(['gsuite_error'=>['GSuite認證錯誤']]);
        }

    }
}
