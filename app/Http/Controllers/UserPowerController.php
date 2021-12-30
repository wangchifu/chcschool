<?php

namespace App\Http\Controllers;

use App\User;
use App\UserPower;
use Illuminate\Http\Request;

class UserPowerController extends Controller
{
    public function create($module,$type)
    {
        $users = User::where('disable',null)
            ->where('username','<>','admin')
            ->orderBy('order_by')
            ->pluck('name','id')
            ->toArray()
            ;

        $data = [
            'users'=>$users,
            'module'=>$module,
            'type'=>$type,
        ];

        return view('user_powers.create',$data);
    }

    public function store(Request $request)
    {
        UserPower::create($request->all());
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function destroy(UserPower $user_power)
    {
        $user_power->delete();
        return redirect()->route('setups.module');
    }
}
