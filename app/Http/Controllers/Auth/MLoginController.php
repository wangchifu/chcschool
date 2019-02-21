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
        if (Auth::attempt([
            'username' => $request->input('username'),
            'password'=>$request->input('password'),
            'disable' => null,
            'login_type'=>'local',
        ])) {
            // 如果認證通過...
            return redirect()->route('index');
        }else{
            $user = User::where('username',$request->input('username'))
                ->first();

            if(empty($user)){
                return back()->withErrors(['error'=>['無此管理帳號']]);
            }else{
                if(password_verify($request->input('password'), $user->password)){
                    if($user->disable == "1"){
                        return back()->withErrors(['error'=>['你被停權了']]);
                    }
                    if($user->login_type == "gsuite"){
                        return back()->withErrors(['error'=>['GSuite帳號不是從這邊登入']]);
                    }
                }else{
                    return back()->withErrors(['error'=>['密碼錯誤！']]);
                }
            }

            return back()->withErrors(['error'=>['錯誤！']]);
        }

    }
}
