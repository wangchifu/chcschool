<?php

namespace App\Http\Controllers;

use App\Content;
use App\Setup;
use App\Log;
use App\Group;
use App\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ContentsController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents = Content::all();
        $tag = null;
        $groups = Group::orderBy('id')->get();
        $group_array = [];
        foreach($groups as $group){
            $group_array[$group->id] = $group->name;
        }
        $data = [
            'contents'=>$contents,
            'tag'=>$tag,
            'group_array'=>$group_array,
        ];
        return view('contents.index',$data);
    }

    public function search($tag=null)
    {
        $contents = Content::where('tags','like','%'.$tag.'%')->get();
        $group_array = [];
        $groups = Group::orderBy('id')->get();
        foreach($groups as $group){
            $group_array[$group->id] = $group->name;
        }
        $data = [
            'contents'=>$contents,
            'tag'=>$tag,
            'group_array'=>$group_array,
        ];
        return view('contents.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::orderBy('id')->get();
        $group_array = [];
        foreach($groups as $group){
            $group_array[$group->id] = $group->name;
        }
        $data = [
            'group_array'=>$group_array,
        ];
        return view('contents.create',$data);
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
            'title' => 'required',
            'content' => 'required',
        ]);
        $att= $request->all();
        $att['tags'] = str_replace(" ","",$att['tags']);
        Content::create($att);
        return redirect()->route('contents.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        $s_key = "cv" . $content->id;
        if (!session($s_key)) {
            $att['views'] = $content->views + 1;
            $content->update($att);            
        }
        $logs_count = Log::where('module','content')->where('this_id',$content->id)->count();
        session([$s_key => '1']);

        $data = [
            'logs_count'=>$logs_count,
            'content'=>$content,
        ];
        return view('contents.show',$data);
    }

    public function show_log($id)
    {
        $logs = Log::where('module','content')
            ->where('this_id',$id)
            ->orderBy('id','DESC')
            ->get();
        $data = [
            'id'=>$id,
            'logs'=>$logs,
        ];
        return view('logs.content_log', $data);
    }

    public function delete_log(Log $log)
    {        
        $log->delete();        
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content)
    {
        $groups = Group::orderBy('id')->get();
        $group_array = [];
        foreach($groups as $group){
            $group_array[$group->id] = $group->name;
        }
        $data = [
            'content'=>$content,
            'group_array'=>$group_array,
        ];
        return view('contents.edit',$data);
    }

    public function together_edit(Content $content)
    {
        if($content->group_id != null){
            $check_edit = UserGroup::where('user_id',auth()->user()->id)->where('group_id',$content->group_id)->first();
            if(empty($check_edit)){
                dd('別想亂來！');
            }
        }else{
            //行政人員預設可以編
            $check_edit = UserGroup::where('user_id',auth()->user()->id)->where('group_id',1)->first();
            if(empty($check_edit)){
                dd('別想亂來！');
            }
        }

        $groups = Group::orderBy('id')->get();
        $group_array = [];
        foreach($groups as $group){
            $group_array[$group->id] = $group->name;
        }
        $data = [
            'content'=>$content,
            'group_array'=>$group_array,
        ];
        return view('contents.together_edit', $data);
    }

    /**
    *public function exec_edit(Content $content)
    *{
    *    return view('contents.exec_edit', compact('content'));
    *}
    *
    */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $att= $request->all();
        $att['tags'] = str_replace(" ","",$att['tags']);
        $content->update($att);

        $att['module'] = "content";
        $att['this_id'] = $content->id;
        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');
        $att['power'] = $request->input('power');
        $att['user_id'] = auth()->user()->id;
        Log::create($att);
        
        return redirect()->route('contents.index');
    }

    public function together_update(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $att= $request->all();
        $att['tags'] = str_replace(" ","",$att['tags']);
        $content->update($att);

        $att['module'] = "content";
        $att['this_id'] = $content->id;
        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');
        $att['power'] = $request->input('power');
        $att['user_id'] = auth()->user()->id;
        Log::create($att);
        
        return redirect()->route('contents.show',$content->id);
    }

    /**
    *public function exec_update(Request $request, Content $content)
    *{
    *    $request->validate([
    *        'title' => 'required',
    *        'content' => 'required',
    *    ]);
    *    $content->update($request->all());
    *
    *    $att['module'] = "content";
    *    $att['this_id'] = $content->id;
    *    $att['title'] = $request->input('title');
    *    $att['content'] = $request->input('content');
    *    $att['power'] = $request->input('power');
    *    $att['user_id'] = auth()->user()->id;
    *    Log::create($att);
    *
    *    return redirect()->route('contents.show', $content->id);
    *}
    */
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Content $content)
    {
        $logs = Log::where('module','content')
        ->where('this_id',$content->id)
        ->delete();

        $content->delete();
        return back();
    }
}
