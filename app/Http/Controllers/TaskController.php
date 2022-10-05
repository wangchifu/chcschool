<?php

namespace App\Http\Controllers;

use App\Setup;
use App\Task;
use App\User;
use App\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['行政待辦'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }

    public function index()
    {
        $school_code = school_code();
        if (empty($_COOKIE['tasks1_' . $school_code]) or empty($_COOKIE['tasks2_' . $school_code])) {
            if (auth()->check()) {
                $this->auth_cookie();
                return redirect()->route('tasks.index');
            } else {
                Session::put('url.intended', route('tasks.index'));
                return redirect()->route('login');
            }
        } else {
            $code =  $_COOKIE['tasks1_' . $school_code];
            $and =  $_COOKIE['tasks2_' . $school_code];
            $u = explode($and, $code);
            $cookie_user_id = $u[1];

            //如果已經登入，檢查和cooekie
            $id = $cookie_user_id;

            if (auth()->check()) {
                if (auth()->user()->id != $cookie_user_id) {
                    $this->auth_cookie();
                    $id = auth()->user()->id;
                }
            }

            $user = User::find($id);

            $user_tasks = UserTask::where('user_id', $user->id)
                ->where('condition', 1)
                ->orderBy('created_at', 'DESC')
                ->get();

            $data = [
                'school_code' => $school_code,
                'user' => $user,
                'user_tasks' => $user_tasks,
            ];
            return view('tasks.index', $data);
        }
    }

    public function index2()
    {
        $school_code = school_code();
        if (empty($_COOKIE['tasks1_' . $school_code]) or empty($_COOKIE['tasks2_' . $school_code])) {
            if (auth()->check()) {
                $this->auth_cookie();
                return redirect()->route('tasks.index');
            } else {
                Session::put('url.intended', route('tasks.index'));
                return redirect()->route('login');
            }
        } else {
            $code =  $_COOKIE['tasks1_' . $school_code];
            $and =  $_COOKIE['tasks2_' . $school_code];
            $u = explode($and, $code);
            $cookie_user_id = $u[1];

            //如果已經登入，檢查和cooekie
            $id = $cookie_user_id;

            if (auth()->check()) {
                if (auth()->user()->id != $cookie_user_id) {
                    $this->auth_cookie();
                    $id = auth()->user()->id;
                }
            }

            $user = User::find($id);

            $user_tasks = UserTask::where('user_id', $user->id)
                ->where('condition', 2)
                ->orderBy('updated_at', 'DESC')
                ->paginate(20);

            $data = [
                'school_code' => $school_code,
                'user' => $user,
                'user_tasks' => $user_tasks,
            ];
            return view('tasks.index2', $data);
        }
    }

    public function index3()
    {
        $school_code = school_code();
        if (empty($_COOKIE['tasks1_' . $school_code]) or empty($_COOKIE['tasks2_' . $school_code])) {
            if (auth()->check()) {
                $this->auth_cookie();
                return redirect()->route('tasks.index');
            } else {
                Session::put('url.intended', route('tasks.index'));
                return redirect()->route('login');
            }
        } else {
            $code =  $_COOKIE['tasks1_' . $school_code];
            $and =  $_COOKIE['tasks2_' . $school_code];
            $u = explode($and, $code);
            $cookie_user_id = $u[1];

            //如果已經登入，檢查和cooekie
            $id = $cookie_user_id;

            if (auth()->check()) {
                if (auth()->user()->id != $cookie_user_id) {
                    $this->auth_cookie();
                    $id = auth()->user()->id;
                }
            }

            $user = User::find($id);

            $user_tasks = UserTask::where('user_id', $user->id)
                ->where('condition', 3)
                ->orderBy('updated_at', 'DESC')
                ->paginate(20);

            $data = [
                'school_code' => $school_code,
                'user' => $user,
                'user_tasks' => $user_tasks,
            ];
            return view('tasks.index3', $data);
        }
    }

    public function self()
    {
        $school_code = school_code();
        if (empty($_COOKIE['tasks1_' . $school_code]) or empty($_COOKIE['tasks2_' . $school_code])) {
            if (auth()->check()) {
                $this->auth_cookie();
                return redirect()->route('tasks.self');
            } else {
                Session::put('url.intended', route('tasks.self'));
                return redirect()->route('login');
            }
        } else {
            $code =  $_COOKIE['tasks1_' . $school_code];
            $and =  $_COOKIE['tasks2_' . $school_code];
            $u = explode($and, $code);
            $cookie_user_id = $u[1];

            //如果已經登入，檢查和cooekie
            $id = $cookie_user_id;

            if (auth()->check()) {
                if (auth()->user()->id != $cookie_user_id) {
                    $this->auth_cookie();
                    $id = auth()->user()->id;
                }
            }

            $user = User::find($id);

            $data = [
                'school_code' => $school_code,
                'user' => $user,
            ];
            return view('tasks.self', $data);
        }
    }

    public function logout()
    {
        $school_code = school_code();
        setcookie("tasks1_" . $school_code, null);
        setcookie("tasks2_" . $school_code, null);
        if (auth()->check()) {
            auth()->logout();
        }
        return redirect()->route('tasks.index');
    }

    public function store(Request $request)
    {
        $att = $request->all();
        $task = Task::create($att);

        $school_code = school_code();
        $folder = 'tasks/' . $task->id;

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $info = [
                    //'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];

                $file->storeAs('privacy/' . $school_code . '/' . $folder, $info['original_filename']);
            }
        }

        $users = User::where('disable', null)->where('username', '<>', 'admin')->get();
        $all = [];
        foreach ($users as $user) {
            $one = [
                'user_id' => $user->id,
                'task_id' => $task->id,
                'condition' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            array_push($all, $one);
        }

        UserTask::insert($all);

        return redirect()->route('tasks.index');
    }

    public function self_store(Request $request)
    {
        $att = $request->all();
        $task = Task::create($att);

        $school_code = school_code();
        $folder = 'tasks/' . $task->id;

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $info = [
                    //'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];

                $file->storeAs('privacy/' . $school_code . '/' . $folder, $info['original_filename']);
            }
        }

        $one = [
            'user_id' => $att['user_id'],
            'task_id' => $task->id,
            'condition' => 1,
        ];
        UserTask::create($one);
        return redirect()->route('tasks.index');
    }

    public function disable(Task $task)
    {
        $school_code = school_code();
        $code =  $_COOKIE['tasks1_' . $school_code];
        $and =  $_COOKIE['tasks2_' . $school_code];
        $u = explode($and, $code);
        $user_id = $u[1];

        $user = User::find($user_id);

        if ($user->id == $task->user_id) {
            $att['disable'] = 1;
            $task->update($att);
        }

        return redirect()->route('tasks.index');
    }

    public function user_condition(Request $request)
    {
        $select_user_task = UserTask::find($request->input('user_task_id'));
        $att['condition'] = $request->input('condition');
        $select_user_task->update($att);


        if ($request->input('old_condition') == "1") {
            $user_tasks = UserTask::where('user_id', $request->input('user_id'))
                ->where('condition', $request->input('old_condition'))
                ->orderBy('updated_at', 'DESC')
                ->get();
        } else {
            $user_tasks = UserTask::where('user_id', $request->input('user_id'))
                ->where('condition', $request->input('old_condition'))
                ->orderBy('updated_at', 'DESC')
                ->paginate(20);
        }

        $check_user_tasks = UserTask::where('user_id', $request->input('user_id'))
            ->where('condition', $request->input('old_condition'))
            ->orderBy('updated_at', 'DESC')
            ->get();

        $result['count'] = count($check_user_tasks);


        $school_code = school_code();

        $result['school_code'] = $school_code;
        $result['token'] = $request->input('_token');
        $result['old_condition'] = $request->input('old_condition');
        $k = 0;
        foreach ($user_tasks as $user_task) {
            $result['user_task'][$k] = [
                'user_task_id' => $user_task->id,
                'task_id' => $user_task->task_id,
                'title' => $user_task->task->title,
                'disable' => $user_task->task->disable,
                'name' => $user_task->task->user->name,
                'created_at' => str_replace(" ", ",", $user_task->task->created_at),
                'user_id' => $user_task->task->user->id
            ];
            $files = get_files(storage_path('app/privacy/' . $school_code . '/tasks/' . $user_task->task_id));
            $result['files'][$k][1] = 0;
            if (!empty($files)) {
                $n = 1;
                foreach ($files as $k1 => $v1) {
                    $file = $school_code . "/tasks/" . $user_task->task_id . "/" . $v1;
                    $file = str_replace('/', '&', $file);
                    $result['files'][$k][$n] = $file;
                    $n++;
                }
            }
            $k++;
        }

        echo json_encode($result);
        return;
    }

    public function auth_cookie()
    {
        $user_id = auth()->user()->id;
        $and = generateRandomString(4);
        $r1 = generateRandomString(10);
        $r2 = generateRandomString(10);
        $token1 =  bcrypt($r1);
        $token2 =  bcrypt($r2);
        $code = $token1 . $and . $user_id . $and . $token2;
        $school_code = school_code();
        setcookie("tasks1_" . $school_code, $code, time() + 31556926);
        setcookie("tasks2_" . $school_code, $and, time() + 31556926);
    }
}
