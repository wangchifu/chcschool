<?php

namespace App\Http\Controllers;

use App\Fix;
use App\FixClass;
use App\Fun;
use App\Http\Requests\FixRequest;
use App\Setup;
use App\User;
use App\UserPower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FixController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['報修系統'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index()
    {
        $fixes = Fix::orderBy('id', 'DESC')
            ->paginate(20);
        $fix_admin = check_power('報修系統', 'A', auth()->user()->id);
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'fixes' => $fixes,
            'fix_admin' => $fix_admin,
            'types'=>$types,
        ];
        return view('fixes.index', $data);
    }
    public function search($situation)
    {
        $fixes = Fix::where('situation', $situation)
            ->orderBy('id', 'DESC')
            ->paginate(20);
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'situation' => $situation,
            'fixes' => $fixes,
            'types'=>$types,
        ];
        return view('fixes.search', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fix_classes = FixClass::where('disable',null)->orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'types'=>$types,
        ];
        return view('fixes.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $att['type'] = $request->input('type');
        $att['user_id'] = auth()->user()->id;
        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');
        $att['situation'] = "3";

        Fix::create($att);

        $att2['email'] = $request->input('email');
        $user = User::find(auth()->user()->id);
        $user->update($att2);

        //寄信給管理者
        $user_powers = UserPower::where('name', '報修系統')
            ->where('type', 'A')
            ->get();

        foreach ($user_powers as $user_power) {
            if (!empty($user_power->user->email)) {
                $subject = '學校網站中「' . auth()->user()->name . '」在「報修設備」寫了：' . $att['title'];
                $body = $att['content'];
                send_mail($user_power->user->email, $subject, $body);
            }
        }

        foreach ($user_powers as $user_power) {
            if (!empty($user_power->user->line_key)) {
                $subject = '學校網站中「' . auth()->user()->name . '」在「報修設備」寫了：' . $att['title'];
                $body = $att['content'];
                $string = $subject."\n\n".$body;
                line_notify($user_power->user->line_key,$string);
            }
        }
        return redirect()->route('fixes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Fix $fix)
    {
        $fix_admin = check_power('報修系統', 'A', auth()->user()->id);
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'fix' => $fix,
            'fix_admin' => $fix_admin,
            'types'=>$types,
        ];
        return view('fixes.show', $data);
    }

    public function show_clean(Fix $fix)
    {
        $fix_admin = null;
        if(auth()->check()){
            $fix_admin = check_power('報修系統', 'A', auth()->user()->id);
        }
        
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'fix' => $fix,
            'fix_admin' => $fix_admin,
            'types'=>$types,
        ];
        return view('fixes.show_clean', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fix $fix)
    {
        $fix->update($request->all());

        $att['email'] = $request->input('email');
        $user = User::find(auth()->user()->id);
        $user->update($att);

        //寄信
        if (!empty($fix->user->email)) {
            $situation = ['2' => '處理中', '1' => '處理完畢'];

            $subject = '回覆「學校網站」中「報修設備」的問題';
            $body = "您問到：\r\n";
            $body .= $request->input('title') . "\r\n";
            $body .= "\r\n系統管理員回覆：\r\n";
            $body .= $situation[$request->input('situation')] . "\r\n";
            $body .= $request->input('reply');
            $body .= "\r\n-----這是系統信件，請勿回信-----";
            send_mail($fix->user->email, $subject, $body);
        }


        return redirect()->route('fixes.show', $fix->id);
    }

    public function update_clean(Request $request, Fix $fix)
    {
        $fix->update($request->all());

        $att['email'] = $request->input('email');
        $user = User::find(auth()->user()->id);
        $user->update($att);

        //寄信
        if (!empty($fix->user->email)) {
            $situation = ['2' => '處理中', '1' => '處理完畢'];

            $subject = '回覆「學校網站」中「報修設備」的問題';
            $body = "您問到：\r\n";
            $body .= $request->input('title') . "\r\n";
            $body .= "\r\n系統管理員回覆：\r\n";
            $body .= $situation[$request->input('situation')] . "\r\n";
            $body .= $request->input('reply');
            $body .= "\r\n-----這是系統信件，請勿回信-----";
            send_mail($fix->user->email, $subject, $body);
        }


        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function edit_class()
    {
        $fix_classes = FixClass::orderBy('order_by')->get();
        $types = [];
        foreach($fix_classes as $fix_class){
            $types[$fix_class->id] = $fix_class->name;
        } 
        $data = [
            'fix_classes'=>$fix_classes,
            'types'=>$types,
        ];

        return view('fixes.edit_class',$data);
    }

    public function store_class(Request $request)
    {
        $att = $request->all();
        if(!isset($att['disable'])) $att['disable'] = null;
        FixClass::create($att);
        return redirect()->route('fixes.edit_class');
    }

    public function update_class(Request $request,FixClass $fix_class)
    {
        $att = $request->all();
        if(!isset($att['disable'])) $att['disable'] = null;
        $fix_class->update($att);
        return redirect()->route('fixes.edit_class');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fix $fix)
    {
        $fix->delete();
        return redirect()->route('fixes.index');
    }

    public function destroy_clean(Fix $fix)
    {
        $fix->delete();
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    function store_notify(Request $request){
        $att['line_key'] =  $request->input('line_key');
        $att['email'] =  $request->input('email');
        $user = User::where('id',auth()->user()->id)->first();
        $user->update($att);
        return redirect()->route('fixes.index');
    }
}
