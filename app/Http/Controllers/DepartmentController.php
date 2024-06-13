<?php

namespace App\Http\Controllers;

use App\Department;
use App\Setup;
use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DepartmentController extends Controller
{

    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['學校介紹'])) {
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
        $departments = Department::orderBy('order_by')->get();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        Department::create($request->all());
        return redirect()->route('departments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        $s_key = "dv" . $department->id;
        if (!session($s_key)) {
            $att['views'] = $department->views + 1;
            $department->update($att);            
        }
        session([$s_key => '1']);

        $logs_count = Log::where('module','department')->where('this_id',$department->id)->count();
        session([$s_key => '1']);

        $data = [
            'logs_count'=>$logs_count,
            'department'=>$department,
        ];
        return view('departments.show',$data);
    }

    public function show_log($id)
    {
        $logs = Log::where('module','department')
            ->where('this_id',$id)
            ->orderBy('id','DESC')
            ->get();
        $data = [
            'id'=>$id,
            'logs'=>$logs,
        ];
        return view('logs.department_log', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function exec_edit(Department $department)
    {
        return view('departments.exec_edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $department->update($request->all());

        $att['module'] = "department";
        $att['this_id'] = $department->id;
        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');
        $att['user_id'] = auth()->user()->id;
        Log::create($att);
        return redirect()->route('departments.index');
    }

    public function exec_update(Request $request, Department $department)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $department->update($request->all());

        $att['module'] = "department";
        $att['this_id'] = $department->id;
        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');
        $att['user_id'] = auth()->user()->id;
        Log::create($att);
        return redirect()->route('departments.show', $department->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $logs = Log::where('module','department')
            ->where('this_id',$department->id)
            ->delete();
        $department->delete();
        return redirect()->route('departments.index');
    }
}
