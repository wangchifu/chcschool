<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeetingRequest;
use App\Meeting;
use App\Report;
use App\Setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MeetingController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['會議文稿'])) {
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
        $meetings = Meeting::orderBy('open_date', 'DESC')->paginate(20);
        $data = [
            'meetings' => $meetings,
        ];
        return view('meetings.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('meetings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $att['open_date'] = $request->input('open_date');
        $att['name'] = $request->input('name');
        $check_meeting = Meeting::where('open_date', $att['open_date'])->where('name', $att['name'])->first();
        if (!empty($check_meeting)) {
            $words = "該日已有相同名稱的會議了！";
            return view('layouts.error', compact('words'));
        }

        Meeting::create($att);
        return redirect()->route('meetings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Meeting $meeting)
    {
        $reports = Report::where('meeting_id', $meeting->id)
            ->orderBy('order_by')
            ->get();

        $has_report = 0;
        foreach ($reports as $report) {
            if ($has_report == 0) {
                $has_report = (auth()->user()->id == $report->user_id) ? "1" : "0";
            }
        }

        $open_date = str_replace('-', '', $meeting->open_date);
        $die_line = (date('Ymd') > $open_date) ? "1" : "0";

        $data = [
            'meeting' => $meeting,
            'reports' => $reports,
            'has_report' => $has_report,
            'die_line' => $die_line,
        ];
        return view('meetings.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
    {
        return view('meetings.edit', compact('meeting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meeting $meeting)
    {
        $meeting->update($request->all());
        return redirect()->route('meetings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meeting $meeting)
    {
        $reports = Report::where('meeting_id', $meeting->id)
            ->get();
        $school_code = school_code();

        foreach ($reports as $report) {

            $folder = storage_path('app/privacy/' . $school_code . '/reports/' . $report->id);
            if (is_dir($folder)) {
                if ($handle = opendir($folder)) { //開啟現在的資料夾
                    while (false !== ($file = readdir($handle))) {
                        //避免搜尋到的資料夾名稱是false,像是0
                        if ($file != "." && $file != "..") {
                            //去除掉..跟.
                            unlink($folder . '/' . $file);
                        }
                    }
                    closedir($handle);
                }
                rmdir($folder);
            }

            $report->delete();
        }
        $meeting->delete();
        return redirect()->route('meetings.index');
    }

    public function txtDown(Meeting $meeting)
    {
        $filename = $meeting->open_date . "_" . $meeting->name . ".txt";
        $txtDown = $meeting->open_date . "_" . $meeting->name . "\r\n";     
        $reports = Report::where('meeting_id', $meeting->id)
            ->orderBy('order_by')
            ->get();   
        foreach ($reports as $report) {
            $txt = "●" . $report->job_title . " " . $report->user->name . "\r\n" . $report->content . "\r\n \r\n";
            $order_by = (empty($report->order_by))?"-".$report->id:$report->order_by;
            $ori[$order_by] = $txt;
        }
        
        ksort($ori);
        
        foreach ($ori as $k => $v) {
            $txtDown .= $v;
        }
        header("Content-disposition: attachment;filename=$filename");
        header("Content-type: text/text ; Charset=utf8");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $txtDown;
    }
}
