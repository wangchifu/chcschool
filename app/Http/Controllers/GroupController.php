<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use App\UserGroup;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //宣告
        $user_group_data = [];

        $groups = Group::orderBy('id')->get();
        $users_groups = UserGroup::all();

        //使用者和群組的矩陣，如 $user_group_data[1] = [1,2,3];
        foreach($users_groups as $user_group){
            if(!isset($user_group_data[$user_group->group_id]))
                $user_group_data[$user_group->group_id] = [];
            array_push($user_group_data[$user_group->group_id], $user_group->user_id);
        }

        $data = [
            'groups'=>$groups,
            'user_group_data'=>$user_group_data,
        ];

        return view('groups.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
        ]);
        Group::create($request->all());
        return redirect()->route('groups.index');
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
    public function edit(Group $group)
    {
        return view('groups.edit',compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name'=>'required',
        ]);
        $att['name'] = $request->input('name');
        $att['disable'] = $request->input('disable');
        $group->update($att);
        return redirect()->route('groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //先刪群組對照表
        UserGroup::where('group_id',$group->id)->delete();
        //再刪群組
        $group->delete();
        return redirect()->route('groups.index');
    }

    public function users_groups(Group $group)
    {
        //宣告
        $user_data = [];
        $user_menu = [];

        //已在此群組的使用者
        $users_groups = UserGroup::where('group_id',$group->id)->get();

        $user_in = [];
        foreach($users_groups as $user_group){
            $user_data[$user_group->user_id]['id'] = $user_group->user_id;
            $user_data[$user_group->user_id]['username'] = $user_group->user->username;
            $user_data[$user_group->user_id]['name'] = $user_group->user->name;
            $user_data[$user_group->user_id]['title'] = $user_group->user->title;
            $user_data[$user_group->user_id]['disable'] = $user_group->user->disable;
            array_push($user_in,$user_group->user_id);
        }

        $users = User::where('disable',null)
            ->orderBy('order_by')
            ->get();

        //列出尚未加入此群組的使用者名單
        foreach($users as $user){
            if(!in_array($user->id,$user_in)){
                $user_menu[$user->id] = $user->job_title."-".$user->name."(".$user->username.")";
            }
        }


        $data = [
            'group'=>$group,
            'users'=>$users,
            'user_data'=>$user_data,
            'user_menu'=>$user_menu,
        ];
        return view('groups.users_groups',$data);
    }

    public function users_groups_store(Request $request)
    {
        $group_id = $request->input('group_id');
        $insert_data = [];
        foreach($request->input('user_id') as $user_id){
            $one = [
                'group_id'=>$group_id,
                'user_id'=>$user_id,
            ];
            array_push($insert_data,$one);
        }
        UserGroup::insert($insert_data);

        return redirect()->route('users_groups',$group_id);
    }

    public function users_groups_destroy(Request $request)
    {
        $user_id = $request->input('user_id');
        $group_id = $request->input('group_id');
        UserGroup::where('group_id',$group_id)
            ->where('user_id',$user_id)
            ->delete();
        return redirect()->route('users_groups',$group_id);
    }

}
