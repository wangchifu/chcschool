<?php

namespace App\Http\Controllers;

use App\LunchSetup;
use Illuminate\Http\Request;

class LunchSetupController extends Controller
{
    public function index()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);

        $lunch_setups = LunchSetup::orderBy('semester','DESC')
            ->paginate(10);
        $data = [
            'admin'=>$admin,
            'lunch_setups'=>$lunch_setups,
        ];

        return view('lunch_setups.index',$data);
    }

    public function create()
    {
        return view('lunch_setups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester'=>'required|numeric',
            'die_line'=>'required|numeric',
            'all_rece_name'=>'required',
            'all_rece_date'=>'required|date',
            'all_rece_num'=>'required|numeric',
            'file1'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file2'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file3'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file4'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $att['semester']=$request->input('semester');
        $att['die_line']=$request->input('die_line');
        if($request->input('teacher_open')){
            $att['teacher_open'] =1;
        }
        if($request->input('disable')){
            $att['disable'] =1;
        }
        $att['all_rece_name']=$request->input('all_rece_name');
        $att['all_rece_date']=$request->input('all_rece_date');
        $att['all_rece_num']=$request->input('all_rece_num');

        $lunch_setup = LunchSetup::create($att);

        $school_code = school_code();

        if ($request->hasFile('file1')) {
            $file = $request->file('file1');

            $file->storeAs('privacy/'.$school_code.'/lunches/'.$lunch_setup->id, 'seal1.png');
        }
        if ($request->hasFile('file2')) {
            $file = $request->file('file2');

            $file->storeAs('privacy/'.$school_code.'/lunches/'.$lunch_setup->id, 'seal2.png');
        }
        if ($request->hasFile('file3')) {
            $file = $request->file('file3');

            $file->storeAs('privacy/'.$school_code.'/lunches/'.$lunch_setup->id, 'seal3.png');
        }
        if ($request->hasFile('file4')) {
            $file = $request->file('file4');

            $file->storeAs('privacy/'.$school_code.'/lunches/'.$lunch_setup->id, 'seal4.png');
        }

        return redirect()->route('lunch_setups.index');
    }

    public function edit(LunchSetup $lunch_setup)
    {
        $data = [
            'lunch_setup'=>$lunch_setup,
        ];

        return view('lunch_setups.edit',$data);
    }

    public function update(Request $request,LunchSetup $lunch_setup)
    {
        $request->validate([
            'semester'=>'required|numeric',
            'die_line'=>'required|numeric',
            'all_rece_name'=>'required',
            'all_rece_date'=>'required|date',
            'all_rece_num'=>'required|numeric',
            'file1'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file2'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file3'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file4'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $att['semester']=$request->input('semester');
        $att['die_line']=$request->input('die_line');
        if($request->input('teacher_open')){
            $att['teacher_open'] =1;
        }else{
            $att['teacher_open'] = null;
        }
        if($request->input('disable')){
            $att['disable'] =1;
        }else{
            $att['disable'] =null;
        }
        $att['all_rece_name']=$request->input('all_rece_name');
        $att['all_rece_date']=$request->input('all_rece_date');
        $att['all_rece_num']=$request->input('all_rece_num');

        $lunch_setup->update($att);

        $school_code = school_code();

        if ($request->hasFile('file1')) {
            $file = $request->file('file1');

            $file->storeAs('privacy/'.$school_code.'/lunches/'.$lunch_setup->id, 'seal1.png');
        }
        if ($request->hasFile('file2')) {
            $file = $request->file('file2');

            $file->storeAs('privacy/'.$school_code.'/lunches/'.$lunch_setup->id, 'seal2.png');
        }
        if ($request->hasFile('file3')) {
            $file = $request->file('file3');

            $file->storeAs('privacy/'.$school_code.'/lunches/'.$lunch_setup->id, 'seal3.png');
        }
        if ($request->hasFile('file4')) {
            $file = $request->file('file4');

            $file->storeAs('privacy/'.$school_code.'/lunches/'.$lunch_setup->id, 'seal4.png');
        }

        return redirect()->route('lunch_setups.index');
    }

    public function destroy(LunchSetup $lunch_setup)
    {
        $school_code = school_code();
        delete_dir(storage_path('app/privacy/'.$school_code.'/lunches/'.$lunch_setup->id));
        $lunch_setup->delete();
        return redirect()->route('lunch_setups.index');
    }
}
