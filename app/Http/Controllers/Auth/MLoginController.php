<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MLoginController extends Controller
{
    //停用者不得登入
    public function auth(Request $request)
    {
        if(session('login_error') >= 3){
            return view('errors.500');
        }

        if($request->input('chaptcha') != session('chaptcha')){
            if(!session('login_error')){
                session(['login_error' => '1']);
            }else{
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }
            return back()->withErrors(['gsuite_error'=>['驗證碼錯誤！']]);
        }

        if (Auth::attempt([
            'username' => $request->input('username'),
            'password'=>$request->input('password'),
            'disable' => null,
            'login_type'=>'local',
        ])) {
            // 如果認證通過...
            return redirect()->route('index');
        }else{

            if(!session('login_error')){
                session(['login_error' => 1]);
            }else{
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }

            $user = User::where('username',$request->input('username'))
                ->first();

            if(empty($user)){
                return back()->withErrors(['error'=>['帳號或密碼錯誤']]);
            }else{
                if(password_verify($request->input('password'), $user->password)){
                    if($user->disable == "1"){
                        return back()->withErrors(['error'=>['你被停權了']]);
                    }
                    if($user->login_type == "gsuite"){
                        return back()->withErrors(['error'=>['GSuite帳號不是從這邊登入']]);
                    }
                }else{
                    return back()->withErrors(['error'=>['帳號或密碼錯誤']]);
                }
            }

            return back()->withErrors(['error'=>['錯誤！']]);
        }

    }
}
