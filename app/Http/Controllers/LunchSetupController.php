<?php

namespace App\Http\Controllers;

use App\LunchFactory;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchPlace;
use App\LunchSetup;
use App\LunchTeaDate;
use Illuminate\Http\Request;

class LunchSetupController extends Controller
{
    public function index()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);

        $lunch_setups = LunchSetup::orderBy('semester','DESC')
            ->paginate(10);
        $places = LunchPlace::orderBy('disable')->get();
        $factories = LunchFactory::orderBy('disable')->get();
        $data = [
            'admin'=>$admin,
            'lunch_setups'=>$lunch_setups,
            'places'=>$places,
            'factories'=>$factories,
        ];

        return view('lunch_setups.index',$data);
    }

    public function create()
    {
        $admin = check_power('午餐系統','A',auth()->user()->id);
        $data = [
            'admin'=>$admin,
        ];
        return view('lunch_setups.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester'=>'required|numeric',
            'die_line'=>'required|numeric',
            'all_rece_name'=>'required',
            'all_rece_date'=>'required|date',
            'all_rece_no'=>'required',
            'all_rece_num'=>'required|numeric',
            'teacher_money'=>'required|numeric',
            'file1'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file2'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file3'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file4'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $att['semester']=$request->input('semester');

        //不得重復同學期
        $check_semester = LunchSetup::where('semester',$att['semester'])->first();
        if($check_semester){
            return back()->withErrors(['errors'=>['此學期已建置！']]);
        }

        $eat_styles = $request->input('eat_styles');

        $eat_str = "";
        foreach($eat_styles as $eat_style){
            $eat_str .= $eat_style.",";
        }
        $eat_str = substr($eat_str,0,-1);
        $att['eat_styles'] = $eat_str;

        $att['die_line']=$request->input('die_line');
        if($request->input('teacher_open')){
            $att['teacher_open'] =1;
        }
        if($request->input('disable')){
            $att['disable'] =1;
        }
        $att['all_rece_name']=$request->input('all_rece_name');
        $att['all_rece_date']=$request->input('all_rece_date');
        $att['all_rece_no']=$request->input('all_rece_no');
        $att['all_rece_num']=$request->input('all_rece_num');
        $att['teacher_money']=$request->input('teacher_money');

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
        $admin = check_power('午餐系統','A',auth()->user()->id);

        $data = [
            'lunch_setup'=>$lunch_setup,
            'admin'=>$admin,
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
            'all_rece_no'=>'required',
            'all_rece_num'=>'required|numeric',
            'teacher_money'=>'required|numeric',
            'file1'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file2'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file3'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'file4'=>'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $att['semester']=$request->input('semester');
        $eat_styles = $request->input('eat_styles');

        $eat_str = "";
        foreach($eat_styles as $eat_style){
            $eat_str .= $eat_style.",";
        }
        $eat_str = substr($eat_str,0,-1);
        $att['eat_styles'] = $eat_str;
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
        $att['all_rece_no']=$request->input('all_rece_no');
        $att['all_rece_num']=$request->input('all_rece_num');
        $att['teacher_money']=$request->input('teacher_money');

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

        LunchOrder::where('semester',$lunch_setup->semester)->delete();
        LunchOrderDate::where('semester',$lunch_setup->semester)->delete();
        LunchTeaDate::where('semester',$lunch_setup->semester)->delete();

        $lunch_setup->delete();
        return redirect()->route('lunch_setups.index');
    }

    public function del_file($path,$id)
    {
        $school_code = school_code();
        $path = str_replace('&','/',$path);
        $path = storage_path('app/privacy/'.$school_code.'/'.$path);
        if(file_exists($path)){
            unlink($path);
        }
        return redirect()->route('lunch_setups.edit',$id);
    }

    public function place_add(Request $request)
    {
        $att['name'] = $request->input('name');
        $att['disable'] = ($request->input('disable'))?1:null;
        LunchPlace::create($att);
        return redirect()->route('lunch_setups.index');
    }

    public function place_update(Request $request , LunchPlace $lunch_place)
    {
        $att['name'] = $request->input('name');
        $att['disable'] = ($request->input('disable'))?1:null;
        $lunch_place->update($att);
        return redirect()->route('lunch_setups.index');
    }

    public function factory_add(Request $request)
    {
        $att['name'] = $request->input('name');
        $att['fid'] = $request->input('fid');
        $att['fpwd'] = $request->input('fpwd');
        $att['disable'] = ($request->input('disable'))?1:null;
        LunchFactory::create($att);
        return redirect()->route('lunch_setups.index');
    }

    public function factory_update(Request $request , LunchFactory $lunch_factory)
    {
        $att['name'] = $request->input('name');
        $att['fid'] = $request->input('fid');
        $att['fpwd'] = $request->input('fpwd');
        $att['disable'] = ($request->input('disable'))?1:null;
        $lunch_factory->update($att);
        return redirect()->route('lunch_setups.index');
    }
}
