<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MLoginController extends Controller
{
    public function logins(Request $request)
    {

        return view('auth.logins');
    }

    public function showLoginForm(Request $request)
    {
        $key = rand(10000, 99999);
        session(['chaptcha' => $key]);
        $cht = array(0 => "零", 1 => "壹", 2 => "貳", 3 => "參", 4 => "肆", 5 => "伍", 6 => "陸", 7 => "柒", 8 => "捌", 9 => "玖");
        //$cht = array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",9=>"9");
        $cht_key = "";
        for ($i = 0; $i < 5; $i++) $cht_key .= $cht[substr($key, $i, 1)];

        session(['cht_chaptcha' => $cht_key]);
        
        if (auth()->check()) {
            return redirect()->route('index');
        }
        return view('auth.login');
    }

    public function showLoginForm_close(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('setups.index');
        }
        return view('auth.login_close');
    }
    //停用者不得登入
    public function auth(Request $request)
    {
        if (session('login_error') >= 3) {
            return view('errors.500');
        }

        if ($request->input('chaptcha') != session('chaptcha')) {
            if (!session('login_error')) {
                session(['login_error' => '1']);
            } else {
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }
            return back()->withErrors(['gsuite_error' => ['驗證碼錯誤！']]);
        }

        if (Auth::attempt([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'disable' => null,
            'login_type' => 'local',
        ])) {
            // 如果認證通過...
            return redirect()->route('setups.index');
        } else {

            if (!session('login_error')) {
                session(['login_error' => 1]);
            } else {
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }

            $user = User::where('username', $request->input('username'))
                ->first();

            if (empty($user)) {
                return back()->withErrors(['error' => ['帳號或密碼錯誤']]);
            } else {
                if (password_verify($request->input('password'), $user->password)) {
                    if ($user->disable == "1") {
                        return back()->withErrors(['error' => ['你被停權了']]);
                    }
                    if ($user->login_type == "gsuite") {
                        return back()->withErrors(['error' => ['GSuite帳號不是從這邊登入']]);
                    }
                } else {
                    return back()->withErrors(['error' => ['帳號或密碼錯誤']]);
                }
            }

            return back()->withErrors(['error' => ['錯誤！']]);
        }
    }
}
