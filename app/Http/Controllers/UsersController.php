<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use App\UserGroup;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('disable',null)
            ->orderBy('order_by')
            ->paginate('20');

        $data = [
            'users'=>$users,
        ];
        return view('users.index',$data);
    }

    public function leave()
    {
        $users = User::where('disable','1')
            ->orderBy('order_by')
            ->paginate('20');

        $data = [
            'users'=>$users,
        ];
        return view('users.leave',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::where('disable','=',null)->pluck('name', 'id')->toArray();
        $data = [
            'groups'=>$groups,
        ];
        return view('users.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $school_code = school_code();

        $att['username'] = $request->input('username');
        $att['name'] = $request->input('name');
        $att['title'] = $request->input('title');
        $att['order_by'] = $request->input('order_by');
        $att['password'] = bcrypt('demo1234');
        $att['code'] = $school_code;
        $att['school'] = "本機帳號";
        $att['kind'] = "教職員";
        $att['login_type'] = "local";

        $check_user = User::where('username',$att['username'])->first();

        if($check_user){
            return back()->withErrors(['errors'=>['此帳號已被使用！']]);
        }

        $user = User::create($att);


        $group_id = $request->input('group_id');

        //再批次insert的array
        $all_insert = [];
        if(!empty($group_id)) {
            foreach ($group_id as $k => $v) {
                if($v != null){
                    $one = [
                        'user_id' => $user->id,
                        'group_id' => $v
                    ];
                    array_push($all_insert, $one);
                }
            }
            if(!empty($all_insert)){
                UserGroup::insert($all_insert);
            }
        }

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function back_pwd(User $user)
    {
        $att['password'] = bcrypt('demo1234');
        $user->update($att);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $groups = Group::where('disable','=',null)->pluck('name', 'id')->toArray();

        $default_group = UserGroup::where('user_id',$user->id)->pluck('group_id')->toArray();
        $data = [
            'user'=>$user,
            'groups'=>$groups,
            'default_group'=>$default_group,
        ];
        return view('users.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        $request->validate([
            'order_by' => ['nullable','numeric']
        ]);
        $att['title'] = $request->input('title');
        $att['name'] = $request->input('name');
        $att['order_by'] = $request->input('order_by');
        $att['group_id'] = $request->input('group_id');
        $att['disable'] = $request->input('disable');
        $att['admin'] = $request->input('admin');
        if($user->username=="admin") $att['admin']=1;//系統管理員永遠是1
        $user->update($att);


        $group_id = $request->input('group_id');
        //全刪使用者群組資料
        UserGroup::where('user_id',$user->id)->delete();

        //再批次insert的array
        $all_insert = [];
        if(!empty($group_id)) {
            foreach ($group_id as $k => $v) {
                if($v != null){
                    $one = [
                        'user_id' => $user->id,
                        'group_id' => $v
                    ];
                    array_push($all_insert, $one);
                }
            }
            if(!empty($all_insert)){
                UserGroup::insert($all_insert);
            }
        }

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

    }
}
