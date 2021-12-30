<?php

namespace App\Http\Controllers;

use App\Fix;
use App\Fun;
use App\Http\Requests\FixRequest;
use App\User;
use App\UserPower;
use Illuminate\Http\Request;

class FixController extends Controller
{
    public function __construct()
    {
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
        $fixes = Fix::orderBy('id','DESC')
            ->paginate(20);
        $fix_admin = check_power('報修系統','A',auth()->user()->id);
        $data= [
            'fixes'=>$fixes,
            'fix_admin'=>$fix_admin,
        ];
        return view('fixes.index',$data);
    }
    public function search($situation)
    {
        $fixes = Fix::where('situation',$situation)
            ->orderBy('id','DESC')
            ->paginate(20);
        $data = [
            'situation'=>$situation,
            'fixes'=>$fixes,
        ];
        return view('fixes.search',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fixes.create');
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
        $user_powers = UserPower::where('name','報修系統')
            ->where('type','A')
            ->get();

        foreach($user_powers as $user_power){
            if(!empty($user_power->user->email)){
                $subject = '學校網站中「'.auth()->user()->name.'」在「報修設備」寫了：'.$att['title'];
                $body = $att['content'];
                send_mail($user_power->user->email,$subject,$body);
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
        $fix_admin = check_power('報修系統','A',auth()->user()->id);
        $data = [
            'fix'=>$fix,
            'fix_admin'=>$fix_admin,
        ];
        return view('fixes.show',$data);
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
        if(!empty($fix->user->email)){
            $situation=['2'=>'處理中','1'=>'處理完畢'];

            $subject = '回覆「學校網站」中「報修設備」的問題';
            $body = "您問到：\r\n";
            $body .= $request->input('title')."\r\n";
            $body .= "\r\n系統管理員回覆：\r\n";
            $body .= $situation[$request->input('situation')]."\r\n";
            $body .= $request->input('reply');
            $body .= "\r\n-----這是系統信件，請勿回信-----";
            send_mail($fix->user->email,$subject,$body);
        }


        return redirect()->route('fixes.show',$fix->id);
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
}
