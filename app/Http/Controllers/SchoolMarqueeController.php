<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Setup;
use App\SchoolMarquee;

class SchoolMarqueeController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
    }

    public function setup(){

        $setup = Setup::first();
        $school_marquees = SchoolMarquee::where('start_date','<=',date('Y-m-d'))
        ->where('stop_date','>=',date('Y-m-d'))
        ->orderBy('id','DESC')
        ->get();
        $data = [
            'setup'=>$setup,
            'school_marquees'=>$school_marquees,
        ];
        return view('school_marquees.setup',$data);
    }

    
    public function index(){
        
        $school_marquees = SchoolMarquee::orderBy('id','DESC')
        ->paginate(10);
        $school_marquee2s = SchoolMarquee::where('start_date','<=',date('Y-m-d'))
        ->where('stop_date','>=',date('Y-m-d'))
        ->orderBy('id','DESC')
        ->get();
        $setup = Setup::first();
        $data = [
            'setup'=>$setup,
            'school_marquees'=>$school_marquees,
            'school_marquee2s'=>$school_marquee2s,
        ];
        return view('school_marquees.index',$data);
    }

    public function create(){
        
        $data = [
            
        ];
        return view('school_marquees.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'stop_date' => 'required',
        ]);

        $att['title'] = $request->input('title');
        $att['start_date'] = $request->input('start_date');
        $att['stop_date'] = $request->input('stop_date');
        $att['user_id'] = auth()->user()->id;
        SchoolMarquee::create($att);
        return redirect()->route('school_marquee.index');
    }

    public function edit(SchoolMarquee $school_marquee){
        if($school_marquee->user_id != auth()->user()->id){
            if(!auth()->user()->admin){
                return back();
            }            
        }
        $data = [
            'school_marquee'=>$school_marquee,
        ];
        return view('school_marquees.edit',$data);
    }

    public function update(Request $request,SchoolMarquee $school_marquee)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'stop_date' => 'required',
        ]);

        $att['title'] = $request->input('title');
        $att['start_date'] = $request->input('start_date');
        $att['stop_date'] = $request->input('stop_date');

        $school_marquee->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function destroy(SchoolMarquee $school_marquee)
    {
        if($school_marquee->user_id != auth()->user()->id){
            if(!auth()->user()->admin){
                return back();
            }            
        }
        $school_marquee->delete();
        return redirect()->route('school_marquee.index');
    }

    public function setup_store(Request $request)
    {
        $setup = Setup::first();
        $att = $request->all();        
        $setup->update($att);
        return redirect()->route('school_marquee.setup');
    }
    
}
