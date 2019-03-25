<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\CalendarWeek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($semester=null)
    {
        $has_week = null;
        $calendar_weeks = [];
        $calendar_d = [];
        $calendar_data = [];
        $this_semester = get_date_semester(date('Y-m-d'));
        $semesters = [];
        //取學期選單
        $ss = DB::select('select semester from calendar_weeks group by semester');
        foreach($ss as $s){
            $semesters[$s->semester] = $s->semester;
        }

        rsort($semesters);

        $semester = ($semester)?$semester:get_date_semester(date('Y-m-d'));

        $calendar_week = CalendarWeek::where('semester',$semester)->first();
        if(!empty($calendar_week)){
            $has_week = 1;
            $calendar_weeks = CalendarWeek::where('semester',$semester)
                ->orderBy('week')
                ->get();

            $calendars = Calendar::where('semester',$semester)
                ->get();

            if(!empty($calendars)) {
                foreach ($calendars as $calendar) {
                    $calendar_d[$calendar->user->order_by][$calendar->calendar_week_id][$calendar->calendar_kind][$calendar->id]['user_id'] = $calendar->user->id;
                    $calendar_d[$calendar->user->order_by][$calendar->calendar_week_id][$calendar->calendar_kind][$calendar->id]['content'] = $calendar->content;
                }

                ksort($calendar_d);

                foreach ($calendar_d as $k1 => $v1) {
                    foreach ($v1 as $k2 => $v2) {
                        foreach ($v2 as $k3 => $v3) {
                            foreach ($v3 as $k4 => $v4) {
                                $calendar_data[$k2][$k3][$k4]['user_id'] = $v4['user_id'];
                                $calendar_data[$k2][$k3][$k4]['content'] = $v4['content'];
                            }
                        }
                    }

                }
            }

        }
        $data = [
            'has_week'=>$has_week,
            'calendar_weeks'=>$calendar_weeks,
            'calendar_data'=>$calendar_data,
            'semesters'=>$semesters,
            'semester'=>$semester,
            'this_semester'=>$this_semester,
        ];
        return view('calendars.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($semester)
    {
        $calendar_weeks = CalendarWeek::where('semester',$semester)
            ->orderBy('week')
            ->get();
        $data = [
            'calendar_weeks'=>$calendar_weeks,
            'semester'=>$semester,
        ];
        return view('calendars.create',$data);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $calendar_weeks = CalendarWeek::where('semester',$request->input('semester'))
            ->orderBy('week')
            ->get();

        $all = [];
        foreach($calendar_weeks as $calendar_week){
            $content = $request->input('w'.$calendar_week->week.'_content');
            foreach($content as $v){
                if(!empty($v)){
                    $att['calendar_week_id'] = $calendar_week->id;
                    $att['semester'] = $request->input('semester');
                    $att['calendar_kind'] = $request->input('calendar_kind');
                    $att['content'] = $v;
                    $att['user_id'] = auth()->user()->id;
                    $att['job_title'] = auth()->user()->title;
                    $att['order_by'] = auth()->user()->order_by;

                    $one = [
                        'calendar_week_id'=>$att['calendar_week_id'],
                        'semester'=>$att['semester'],
                        'calendar_kind'=>$att['calendar_kind'],
                        'content'=>$att['content'],
                        'user_id'=>$att['user_id'],
                        'job_title'=>$att['job_title'],
                        'order_by'=>$att['order_by'],
                        'created_at'=>now(),
                        'updated_at'=>now(),
                    ];

                    array_push($all,$one);
                }
            }
        }

        Calendar::insert($all);

        return redirect()->route('calendars.index');

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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Calendar $calendar)
    {
        if($calendar->user_id != auth()->user()->id){
            $words = "你不要亂來！";
            return view('layouts.error',compact('words'));
        }

        $calendar->delete();

        return redirect()->route('calendars.index');
    }

    public function print($semester=null)
    {
        $has_week = null;
        $calendar_weeks = [];
        $calendar_data = [];
        $this_semester = get_date_semester(date('Y-m-d'));
        $semesters = [];
        $calendar_d = [];
        //取學期選單
        $ss = DB::select('select semester from calendar_weeks group by semester');
        foreach($ss as $s){
            $semesters[$s->semester] = $s->semester;
        }

        rsort($semesters);

        $semester = ($semester)?$semester:get_date_semester(date('Y-m-d'));

        $calendar_week = CalendarWeek::where('semester',$semester)->first();
        if(!empty($calendar_week)){
            $has_week = 1;
            $calendar_weeks = CalendarWeek::where('semester',$semester)
                ->orderBy('week')
                ->get();

            $calendars = Calendar::where('semester',$semester)
                ->get();

            if(!empty($calendars)) {
                foreach ($calendars as $calendar) {
                    $calendar_d[$calendar->user->order_by][$calendar->calendar_week_id][$calendar->calendar_kind][$calendar->id]['user_id'] = $calendar->user->id;
                    $calendar_d[$calendar->user->order_by][$calendar->calendar_week_id][$calendar->calendar_kind][$calendar->id]['content'] = $calendar->content;
                }

                ksort($calendar_d);

                foreach ($calendar_d as $k1 => $v1) {
                    foreach ($v1 as $k2 => $v2) {
                        foreach ($v2 as $k3 => $v3) {
                            foreach ($v3 as $k4 => $v4) {
                                $calendar_data[$k2][$k3][$k4]['user_id'] = $v4['user_id'];
                                $calendar_data[$k2][$k3][$k4]['content'] = $v4['content'];
                            }
                        }
                    }

                }
            }

        }
        $data = [
            'has_week'=>$has_week,
            'calendar_weeks'=>$calendar_weeks,
            'calendar_data'=>$calendar_data,
            'semesters'=>$semesters,
            'semester'=>$semester,
            'this_semester'=>$this_semester,
        ];
        return view('calendars.print',$data);
    }
}
