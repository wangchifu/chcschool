<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Meeting;
use App\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Meeting $meeting)
    {
        return view('reports.create',compact('meeting'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
    {
        $att['user_id'] = auth()->user()->id;
        $att['job_title'] = auth()->user()->job_title;
        $att['meeting_id'] = $request->input('meeting_id');
        $att['content'] = $request->input('content');
        $att['order_by'] = auth()->user()->order_by;
        $report = Report::create($att);

        $folder = 'reports/'.$report->id;
        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    //'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];

                $file->storeAs('public/' . $folder, $info['original_filename']);

            }
        }

        return redirect()->route('meetings.show',$att['meeting_id']);
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
    public function edit(Report $report)
    {
        if($report->user_id != auth()->user()->id){
            $words = "你想做什麼？";
            return view('layouts.error',compact('words'));
        }
        //有無附件
        $files = get_files(storage_path('app/public/reports/'.$report->id));

        $data = [
            'report'=>$report,
            'files'=>$files,
        ];
        return view('reports.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReportRequest $request,Report $report)
    {
        if($report->user_id != auth()->user()->id){
            $words = "你想做什麼？";
            return view('layouts.error',compact('words'));
        }
        $att['job_title'] = auth()->user()->job_title;
        $att['content'] = $request->input('content');
        $report->update($att);

        $folder = 'reports/'.$report->id;
        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    //'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];

                $file->storeAs('public/' . $folder, $info['original_filename']);

            }
        }

        return redirect()->route('meetings.show',$report->meeting_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        if($report->user_id != auth()->user()->id){
            $words = "你想做什麼？";
            return view('layouts.error',compact('words'));
        }

        $folder = storage_path('app/public/reports/'.$report->id);
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) { //開啟現在的資料夾
                while (false !== ($file = readdir($handle))) {
                    //避免搜尋到的資料夾名稱是false,像是0
                    if ($file != "." && $file != "..") {
                        //去除掉..跟.
                        unlink($folder.'/'.$file);
                    }
                }
                closedir($handle);
            }
            rmdir($folder);
        }

        $report->delete();
        return redirect()->route('meetings.show',$report->meeting_id);

    }

    public function fileDel($file)
    {
        $file_array = explode('&',$file);

        $report = Report::where('id',$file_array[1])->first();
        if($report->user_id != auth()->user()->id){
            $words = "你想做什麼？";
            return view('layouts.error',compact('words'));
        }

        $file = str_replace('&','/',$file);
        $file = storage_path('app/public/'.$file);
        if(file_exists($file)){
            unlink($file);
        }


        return redirect()->route('meetings_reports.edit',$file_array[1]);

    }
}
