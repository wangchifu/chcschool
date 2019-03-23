<?php

namespace App\Http\Controllers;

use App\Fix;
use App\Fun;
use App\Http\Requests\FixRequest;
use Illuminate\Http\Request;

class FixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fixes = Fix::where('type','1')
            ->orderBy('id','DESC')
            ->paginate(20);
        return view('fixes.index',compact('fixes'));
    }
    public function search($situation)
    {
        $fixes = Fix::where('type','1')
            ->where('situation',$situation)
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
