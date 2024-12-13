<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsideFileRequest;
use App\InsideFile;
use App\Setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class InsideFilesController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['內部文件'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }


    public function index($path = null)
    {
        $school_code = school_code();

        $folder_path[0] = '根目錄';

        $path_array = explode('&', $path);
        $folder_id = end($path_array);
        if (empty($folder_id)) $folder_id = null;

        foreach ($path_array as $v) {
            if ($v != null) {
                $check = InsideFile::where('id', $v)->first();
                $folder_path[$v] = $check->name;
            }
        }


        //列出目錄
        $folders = InsideFile::where('type', '1')
            ->where('folder_id', $folder_id)
            ->orderBy('name')
            ->get();

        //列出檔案
        $files = InsideFile::where('type', '2')
            ->where('folder_id', $folder_id)
            ->orderBy('name')
            ->get();

        //列出雲端檔案
        $clouds = InsideFile::where('type', '3')
            ->where('folder_id', $folder_id)
            ->orderBy('name')
            ->get();

        //學校目錄
        $f1 = storage_path('app/public/' . $school_code);
        $dir_size1 = get_dir_size($f1);

        $f2 = storage_path('app/privacy/' . $school_code);
        $dir_size2 = get_dir_size($f2);

        $dir_size = $dir_size1 + $dir_size2;

        $size = round($dir_size / 1024, 2);
        $per = round($size * 100 / 5120, 2);

        $data = [
            'school_code' => $school_code,
            'path' => $path,
            'folder_id' => $folder_id,
            'folders' => $folders,
            'folder_path' => $folder_path,
            'files' => $files,
            'clouds' => $clouds,
            'per' => $per,
            'size' => $size,
        ];
        return view('inside_files.index', $data);
    }

    public function create_folder(Request $request)
    {
        if (strpos($request->input('name'), "&")) {
            return back()->withErrors(['error' => ['不得有特殊字元「&」！']]);
        }
        if (strpos($request->input('name'), "\"")) {
            return back()->withErrors(['error' => ['不得有特殊字元「"」！']]);
        }
        if (strpos($request->input('name'), "'")) {
            return back()->withErrors(['error' => ["不得有特殊字元「'」！"]]);
        }
        $school_code = school_code();

        $root = storage_path('app/privacy/' . $school_code . '/inside_files');
        if (!is_dir(storage_path('app/privacy/' . $school_code))) {
            mkdir(storage_path('app/privacy/' . $school_code));
        }
        if (!is_dir($root)) {
            mkdir($root);
        }
        //新增目錄
        $new_path = $root;

        foreach (explode('&', $request->input('path')) as $v) {
            $check = InsideFile::where('id', $v)->first();
            if (!empty($v)) $new_path .= '/' . $check->name;
        }

        $new_path .= '/' . $request->input('name');

        $att['name'] = $request->input('name');
        $att['type'] = 1; //目錄
        $att['user_id'] = auth()->user()->id;
        $att['folder_id'] = $request->input('folder_id');


        if (!is_dir($new_path)) {
            mkdir($new_path);
            InsideFile::create($att);
        } else {
            return back()->withErrors(['error' => ['已有此目錄！']]);
        }
        return redirect()->route('inside_files.index', $request->input('path'));
    }

    public function edit(InsideFile $inside_file, $path)
    {
        $data = [
            'inside_file' => $inside_file,
            'path' => $path,
        ];
        return view('inside_files.edit', $data);
    }

    public function update(Request $request, InsideFile $inside_file)
    {
        if($inside_file->type != 3){
            if (strpos($request->input('name'), "&")) {
                return back()->withErrors(['error' => ['不得有特殊字元「&」！']]);
            }
            if (strpos($request->input('name'), "\"")) {
                return back()->withErrors(['error' => ['不得有特殊字元「"」！']]);
            }
            if (strpos($request->input('name'), "'")) {
                return back()->withErrors(['error' => ["不得有特殊字元「'」！"]]);
            }
        }
        

        $school_code = school_code();

        $path_array = explode('&', $request->input('path'));

        $remove = "inside_files";

        foreach ($path_array as $v) {
            if (!empty($v) and $v != $inside_file->id) {
                $f = InsideFile::where('id', $v)->first();
                $remove .= "/" . $f->name;
            }
        }

        $old_name = storage_path('app/privacy/' . $school_code . '/' . $remove) . '/' . $inside_file->name;
        $new_name = storage_path('app/privacy/' . $school_code . '/' . $remove) . '/' . $request->input('name');


        if ($inside_file->type == "1") {
            if (is_dir($old_name)) {
                rename($old_name, $new_name);
            }
        } elseif ($inside_file->type == "2") {
            if (file_exists($old_name)) {
                rename($old_name, $new_name);
            }
        }


        $att['name'] = $request->input('name');
        $att['url'] = $request->input('url');
        $inside_file->update($att);

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function delete($path)
    {
        $school_code = school_code();

        $path_array = explode('&', $path);
        $id = end($path_array);
        $check = InsideFile::where('id', $id)->first();

        $new_path = "";
        $remove = "inside_files";

        foreach ($path_array as $v) {
            if (!empty($v) and $v != $id) {
                $new_path .= '&' . $v;
            }
            if (!empty($v)) {
                $f = InsideFile::where('id', $v)->first();
                $remove .= "/" . $f->name;
            }
        }

        if ($check->type == "1") {
            if (is_dir(storage_path('app/privacy/' . $school_code . '/' . $remove))) {
                rmdir(storage_path('app/privacy/' . $school_code . '/' . $remove));
            }
        } elseif ($check->type == "2") {
            if (file_exists(storage_path('app/privacy/' . $school_code . '/' . $remove))) {
                unlink(storage_path('app/privacy/' . $school_code . '/' . $remove));
            }
        }

        $check->delete();

        return redirect()->route('inside_files.index', $new_path);
    }

    public function upload_file(InsideFileRequest $request)
    {
        $school_code = school_code();

        $root = storage_path('app/privacy/' . $school_code . '/inside_files');
        $p = 'privacy/' . $school_code . '/inside_files';
        if (!is_dir($root)) {
            mkdir($root);
        }
        //新增目錄
        $new_path = $root;


        foreach (explode('&', $request->input('path')) as $v) {
            $check = InsideFile::where('id', $v)->first();
            if (!empty($v)) {
                $new_path .= '/' . $check->name;
                $p .= '/' . $check->name;
            }
        }



        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $info = [
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ];

                if (strpos($info['original_filename'], "&")) {
                    return back()->withErrors(['error' => ['不得有特殊字元「&」！']]);
                }
                if (strpos($info['original_filename'], "\"")) {
                    return back()->withErrors(['error' => ['不得有特殊字元「"」！']]);
                }
                if (strpos($info['original_filename'], "'")) {
                    return back()->withErrors(['error' => ["不得有特殊字元「'」！"]]);
                }

                $file->storeAs($p, $info['original_filename']);

                $att['name'] = $info['original_filename'];
                $att['type'] = 2; //檔案
                $att['user_id'] = auth()->user()->id;
                $att['folder_id'] = $request->input('folder_id');
                InsideFile::create($att);
            }
        }

        return redirect()->route('inside_files.index', $request->input('path'));
    }

    public function upload_cloud(Request $request)
    {
        $att['name'] = $request->input('name');
        $att['url'] = $request->input('url');
        $att['type'] = 3; //雲端檔案
        $att['user_id'] = auth()->user()->id;        
        $att['folder_id'] = $request->input('folder_id');
        
        InsideFile::create($att);

        return redirect()->route('inside_files.index', $request->input('path'));
    }

    public function download($path)
    {
        $school_code = school_code();

        $path_array = explode('&', $path);
        $file_id = end($path_array);

        $file = "inside_files";
        foreach ($path_array as $v) {
            if ($v != $file_id and !empty($v)) {
                $check = InsideFile::where('id', $v)->first();
                $file .= "&" . $check->name;
            }
        }

        $upload = InsideFile::where('id', $file_id)->first();
        $file .= '&' . $upload->name;

        $file = str_replace('&', '/', $file);
        $file = storage_path('app/privacy/' . $school_code . '/' . $file);
        return response()->download($file);
    }
}
