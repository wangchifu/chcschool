<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Post;
use App\PostType;
use App\Setup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PostsController extends Controller
{

    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }

        $module_setup = get_module_setup();
        if (!isset($module_setup['公告系統'])) {
            echo "<h1>已停用</h1>";
            die();
        }

        /**
        //兩年後自刪公告
        $dt = Carbon::now()->subYears(2);
        $posts = Post::whereDate('created_at','<',substr($dt,0,20))
            ->get();
        foreach($posts as $post){
            $school_code = school_code();
            $folder = storage_path('app/public/'.$school_code.'/posts/'.$post->id);
            if (is_dir($folder)) {
                delete_dir($folder);
            }

            $post->delete();
        }
         *
         * */
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('top', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        $post_types = PostType::orderBy('order_by')->pluck('name', 'id')->toArray();

        $data = [
            'posts' => $posts,
            'post_types' => $post_types,
        ];
        return view('posts.index', $data);
    }

    /**

    public function insite()
    {
        $posts = Post::where('insite','1')
            ->orderBy('top','DESC')
            ->orderBy('created_at','DESC')
            ->paginate(20);
        $data = [
            'posts'=>$posts,
        ];
        return view('posts.insite',$data);
    }

    public function honor()
    {
        $posts = Post::where('insite','2')
            ->orderBy('top','DESC')
            ->orderBy('created_at','DESC')
            ->paginate(20);
        $data = [
            'posts'=>$posts,
        ];
        return view('posts.honor',$data);
    }
     * */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $school_code = school_code();
        //學校目錄
        $f1 = storage_path('app/public/' . $school_code);
        $dir_size1 = get_dir_size($f1);

        $f2 = storage_path('app/privacy/' . $school_code);
        $dir_size2 = get_dir_size($f2);

        $dir_size = $dir_size1 + $dir_size2;

        $size = round($dir_size / 1024, 2);
        $per = round($size * 100 / 5120, 2);

        $all_types = PostType::orderBy('order_by')->pluck('name', 'id')->toArray();
        $types[0] = "一般公告";
        foreach ($all_types as $k => $v) {
            $types[$k] = $v;
        }

        $data = [
            'types' => $types,
            'size' => $size,
            'per' => $per,
        ];
        return view('posts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //處理檔案上傳
        if ($request->hasFile('title_image')) {
            $title_image = $request->file('title_image');
            $att['title_image'] = 1;
        }

        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');
        $att['job_title'] = auth()->user()->title;
        $att['user_id'] = auth()->user()->id;
        $att['views'] = 0;
        $att['insite'] = ($request->input('insite')) ? $request->input('insite') : null;

        $post = Post::create($att);

        $school_code = school_code();
        $folder = 'public/' . $school_code . '/posts/' . $post->id;

        //執行上傳檔案
        if ($request->hasFile('title_image')) {
            $title_image->storeAs($folder, 'title_image.png');
        }

        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $info = [
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ];

                $file->storeAs($folder . '/files', $info['original_filename']);
            }
        }

        if ($att['insite'] == null) {
            return redirect()->route('posts.type', 0);
        } else {
            return redirect()->route('posts.type', $post->insite);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {

        $s_key = "pv" . $post->id;
        if (!session($s_key)) {
            $att['views'] = $post->views + 1;
            $post->update($att);

            $ip = GetIP();
            $school_code = school_code();
            $post_folder = storage_path('app/public/'.$school_code.'/posts/'.$post->id); 
            $file = $post_folder.'/'.$post->id.'.txt';
            if(!is_dir($post_folder)) {
                mkdir($post_folder,0777,true);
            }
            if(!file_exists($file)) {
                $wfile = fopen($file, "w") or die("Unable to open file!");
                fclose($wfile);        
            }
            $wfile = fopen($file, "at") or die("Unable to open file!");
            fputs( $wfile, $att['views']." / ".$ip.' / '.date('Y-m-d H:i:s').PHP_EOL);
            fclose($wfile);
        }
        session([$s_key => '1']);


        $next_post = Post::where('id', '>', $post->id)->first();
        $last_post = Post::where('id', '<', $post->id)
            ->orderBy('id', 'DESC')
            ->first();

        $last_id = (empty($last_post)) ? null : $last_post->id;
        $next_id = (empty($next_post)) ? null : $next_post->id;

        $school_code = school_code();

        //有無附件
        $files = get_files(storage_path('app/public/' . $school_code . '/posts/' . $post->id . '/files'));

        $today = Carbon::today();
        $next_month = $today->subMonth(1);
        $hot_posts = Post::orderBy('views', 'DESC')
            ->where('created_at', '>', $next_month)
            ->paginate(20);

        $data = [
            'school_code' => $school_code,
            'post' => $post,
            'hot_posts' => $hot_posts,
            'last_id' => $last_id,
            'next_id' => $next_id,
            'files' => $files,
        ];

        return view('posts.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if (auth()->user()->id != $post->user_id and auth()->user()->admin != 1) {
            return back();
        }

        $school_code = school_code();

        //有無標題圖片
        $title_image = file_exists(storage_path('app/public/' . $school_code . '/posts/' . $post->id . '/title_image.png'));

        //有無附件
        $files = get_files(storage_path('app/public/' . $school_code . '/posts/' . $post->id . '/files'));

        $school_code = school_code();
        //學校目錄
        $f1 = storage_path('app/public/' . $school_code);
        $dir_size1 = get_dir_size($f1);

        $f2 = storage_path('app/privacy/' . $school_code);
        $dir_size2 = get_dir_size($f2);

        $dir_size = $dir_size1 + $dir_size2;
        $size = round($dir_size / 1024, 2);
        $per = round($size * 100 / 5120, 2);

        $all_types = PostType::orderBy('order_by')->pluck('name', 'id')->toArray();
        $types[0] = "一般公告";
        foreach ($all_types as $k => $v) {
            $types[$k] = $v;
        }

        $data = [
            'post' => $post,
            'files' => $files,
            'title_image' => $title_image,
            'school_code' => $school_code,
            'types' => $types,
            'per' => $per,
            'size' => $size,
        ];

        return view('posts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        //處理檔案上傳
        if ($request->hasFile('title_image')) {
            $title_image = $request->file('title_image');
            $att['title_image'] = 1;
        }

        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');
        $att['insite'] = ($request->input('insite')) ? $request->input('insite') : null;

        $post->update($att);

        $school_code = school_code();
        $folder = 'public/' . $school_code . '/posts/' . $post->id;

        //執行上傳檔案
        if ($request->hasFile('title_image')) {
            $title_image->storeAs($folder, 'title_image.png');
        }

        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $info = [
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ];

                $file->storeAs($folder . '/files', $info['original_filename']);
            }
        }


        if ($att['insite'] == null) {
            return redirect()->route('posts.type', 0);
        } else {
            return redirect()->route('posts.type', $post->insite);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (auth()->user()->id != $post->user_id and auth()->user()->admin != 1) {
            return back();
        }
        $school_code = school_code();
        $folder = storage_path('app/public/' . $school_code . '/posts/' . $post->id);
        if (is_dir($folder)) {
            delete_dir($folder);
        }

        $post->delete();

        return redirect()->route('posts.index');
    }

    public function delete_title_image(Post $post)
    {
        if ($post->user_id != auth()->user()->id and auth()->user()->admin != 1) {
            return back();
        }

        $school_code = school_code();
        $file = storage_path('app/public/' . $school_code . '/posts/' . $post->id . '/title_image.png');

        if (file_exists($file)) {
            unlink($file);
        }

        $att['title_image'] = null;
        $post->update($att);

        return redirect()->route('posts.edit', $post->id);
    }

    public function delete_file(Post $post, $filename)
    {
        if ($post->user_id != auth()->user()->id and auth()->user()->admin != 1) {
            return back();
        }

        $school_code = school_code();
        $file = storage_path('app/public/' . $school_code . '/posts/' . $post->id . '/files/' . $filename);

        if (file_exists($file)) {
            unlink($file);
        }

        $att['title_image'] = null;
        $post->update($att);

        return redirect()->route('posts.edit', $post->id);
    }

    public function search(Request $request, $search = null)
    {
        if ($request->input('check') != session('search') and empty($request->input('page'))) {
            return back()->withErrors(['error' => ['驗證碼不對！']]);
        }
        $search = ($search) ? $search : $request->input('search');

        if (mb_strlen($search) < 2) {
            return back()->withErrors(['error' => ['必須二個字元以上']]);
        }
        $posts = Post::where('content', 'like', '%' . $search . '%')
            ->orWhere('title', 'like', '%' . $search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(20);
        $post_types = PostType::orderBy('order_by')->pluck('name', 'id')->toArray();

        $data = [
            'posts' => $posts,
            'search' => $search,
            'post_types' => $post_types,
        ];
        return view('posts.search', $data);
    }

    public function job_title($job_title)
    {
        $posts = Post::where('job_title', $job_title)
            ->orderBy('top', 'DESC')
            ->orderBy('id', 'DESC')->paginate(20);
        $post_types = PostType::orderBy('order_by')->pluck('name', 'id')->toArray();

        $data = [
            'posts' => $posts,
            'job_title' => $job_title,
            'post_types' => $post_types,
        ];
        return view('posts.job_title', $data);
    }

    public function select_type(Request $request)
    {
        return redirect()->route('posts.type', $request->input('select_type'));
    }

    public function type($type)
    {
        if ($type == "0") $type = null;

        $posts = Post::where('insite', $type)
            ->orderBy('top', 'DESC')
            ->orderBy('id', 'DESC')->paginate(20);

        if ($type == null) {
            $type_name = "一般公告";
        } else {
            $post_type = PostType::where('id', $type)->first();
            $type_name = $post_type->name;
        }
        $post_types = PostType::orderBy('order_by')->pluck('name', 'id')->toArray();

        $data = [
            'posts' => $posts,
            'type_name' => $type_name,
            'post_types' => $post_types,
        ];
        return view('posts.type', $data);
    }

    public function show_type()
    {
        $post_types = PostType::orderBy('order_by')->get();
        $data = [
            'post_types' => $post_types,
        ];
        return view('posts.show_type', $data);
    }

    public function store_type(Request $request)
    {
        $request->validate([
            'order_by' => 'nullable|numeric',
            'name' => 'required',
        ]);
        $att['order_by'] = $request->input('order_by');
        $att['name'] = $request->input('name');

        PostType::create($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function update_type(Request $request, PostType $post_type)
    {
        $request->validate([
            'order_by' => 'nullable|numeric',
            'name' => 'required',
        ]);
        $att['order_by'] = $request->input('order_by');
        $att['name'] = $request->input('name');

        $post_type->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function delete_type(PostType $post_type)
    {
        $att['insite'] = null;
        $posts = Post::where('insite', $post_type->id)->get();
        foreach ($posts as $post) {
            $post->update($att);
        }
        $post_type->delete();
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function top_up(Post $post)
    {
        $att['top'] = 1;
        $post->update($att);
        return redirect()->route('posts.index');
    }

    public function top_down(Post $post)
    {
        $att['top'] = null;
        $post->update($att);
        return redirect()->route('posts.index');
    }
}
