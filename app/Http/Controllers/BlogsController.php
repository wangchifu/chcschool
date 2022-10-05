<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Blog;
use App\Setup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BlogsController extends Controller
{

    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['校園部落格'])) {
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
        $school_code = school_code();

        $blogs = Blog::orderBy('created_at', 'DESC')
            ->paginate(10);


        $data = [
            'blogs' => $blogs,
            'school_code' => $school_code,
        ];
        return view('blogs.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogRequest $request)
    {
        //處理檔案上傳
        if ($request->hasFile('title_image')) {
            $title_image = $request->file('title_image');
            $att['title_image'] = 1;
        }

        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');
        $att['user_id'] = auth()->user()->id;
        $att['views'] = 0;

        $blog = Blog::create($att);

        $school_code = school_code();
        $folder = 'public/' . $school_code . '/blogs/' . $blog->id;

        //執行上傳檔案
        if ($request->hasFile('title_image')) {
            $title_image->storeAs($folder, 'title_image.png');
        }

        return redirect()->route('blogs.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        if (auth()->user()->id != $blog->user_id and auth()->user()->admin != 1) {
            return back();
        }

        $school_code = school_code();

        //有無標題圖片
        $title_image = file_exists(storage_path('app/public/' . $school_code . '/blogs/' . $blog->id . '/title_image.png'));


        $data = [
            'blog' => $blog,
            'title_image' => $title_image,
            'school_code' => $school_code,
        ];

        return view('blogs.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogRequest $request, Blog $blog)
    {

        //處理檔案上傳
        if ($request->hasFile('title_image')) {
            $title_image = $request->file('title_image');
            $att['title_image'] = 1;
        }

        $att['title'] = $request->input('title');
        $att['content'] = $request->input('content');

        $blog->update($att);

        $school_code = school_code();
        $folder = 'public/' . $school_code . '/blogs/' . $blog->id;

        //執行上傳檔案
        if ($request->hasFile('title_image')) {
            $title_image->storeAs($folder, 'title_image.png');
        }

        return redirect()->route('blogs.index');
    }


    public function delete_title_image(Blog $blog)
    {
        if ($blog->user_id != auth()->user()->id) {
            return back();
        }

        $school_code = school_code();
        $file = storage_path('app/public/' . $school_code . '/blogs/' . $blog->id . '/title_image.png');

        if (file_exists($file)) {
            unlink($file);
        }

        $att['title_image'] = null;
        $blog->update($att);

        return redirect()->route('blogs.edit', $blog->id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        if (auth()->user()->id != $blog->user_id and auth()->user()->admin != 1) {
            return back();
        }
        $school_code = school_code();
        $folder = storage_path('app/public/' . $school_code . '/blogs/' . $blog->id);
        if (is_dir($folder)) {
            delete_dir($folder);
        }

        $blog->delete();

        return redirect()->route('blogs.index');
    }





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {

        $s_key = "pv" . $blog->id;
        if (!session($s_key)) {
            $att['views'] = $blog->views + 1;
            $blog->update($att);
        }
        session([$s_key => '1']);


        $next_blog = Blog::where('id', '>', $blog->id)->first();
        $last_blog = Blog::where('id', '<', $blog->id)
            ->orderBy('id', 'DESC')
            ->first();

        $last_id = (empty($last_blog)) ? null : $last_blog->id;
        $next_id = (empty($next_blog)) ? null : $next_blog->id;

        $school_code = school_code();

        $data = [
            'school_code' => $school_code,
            'last_id' => $last_id,
            'next_id' => $next_id,
            'blog' => $blog,
        ];

        return view('blogs.show', $data);
    }
}
