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
        
        $data = [
            
        ];
        return view('school_marquees.setup',$data);
    }

    
    public function index(){
        
        $school_marquees = SchoolMarquee::orderBy('stop_date','DESC')->get();
        $data = [
            'school_marquees'=>$school_marquees,
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

    public function destroy(SchoolMarquee $school_marquee)
    {
        if($school_marquee->user_id != auth()->user()->id){
            return back();
        }
        $school_marquee->delete();
        return redirect()->route('school_marquee.index');
    }
    
}
