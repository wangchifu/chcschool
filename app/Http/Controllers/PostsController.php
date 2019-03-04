<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('top','DESC')
            ->orderBy('created_at','DESC')
            ->paginate(20);
        $data = [
            'posts'=>$posts
        ];
        return view('posts.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
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
        $att['insite'] = $request->input('insite');

        $post = Post::create($att);

        $school_code = school_code();
        $folder = 'public/'. $school_code .'/posts/'.$post->id;

        //執行上傳檔案
        if ($request->hasFile('title_image')) {
            $title_image->storeAs($folder, 'title_image.png');
        }

        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ];

                $file->storeAs($folder.'/files', $info['original_filename']);

            }
        }



        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {

        $s_key = "pv".$post->id;
        if(!session($s_key)){
            $att['views'] = $post->views+1;
            $post->update($att);
        }
        session([$s_key => '1']);


        $next_post = Post::where('id', '>', $post->id)->first();
        $last_post = Post::where('id', '<', $post->id)
            ->orderBy('id','DESC')
            ->first();

        $last_id = (empty($last_post))?null:$last_post->id;
        $next_id = (empty($next_post))?null:$next_post->id;

        $school_code = school_code();

        //有無附件
        $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));

        $today = Carbon::today();
        $next_month = $today->subMonth(1);
        $hot_posts = Post::orderBy('views','DESC')
            ->where('created_at','>',$next_month)
            ->paginate(20);

        $data = [
            'school_code'=>$school_code,
            'post'=>$post,
            'hot_posts'=>$hot_posts,
            'last_id'=>$last_id,
            'next_id'=>$next_id,
            'files'=>$files,
        ];

        return view('posts.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if(auth()->user()->id != $post->user_id){
            return back();
        }

        $school_code = school_code();

        //有無標題圖片
        $title_image = file_exists(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/title_image.png'));

        //有無附件
        $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));


        $data = [
            'post'=>$post,
            'files'=>$files,
            'title_image'=>$title_image,
            'school_code'=>$school_code,
        ];

        return view('posts.edit',$data);
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
        $att['insite'] = $request->input('insite');

        $post->update($att);

        $school_code = school_code();
        $folder = 'public/'. $school_code .'/posts/'.$post->id;

        //執行上傳檔案
        if ($request->hasFile('title_image')) {
            $title_image->storeAs($folder, 'title_image.png');
        }

        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ];

                $file->storeAs($folder.'/files', $info['original_filename']);

            }
        }



        return redirect()->route('posts.show',$post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if(auth()->user()->id != $post->user_id){
            return back();
        }
        $school_code = school_code();
        $folder = storage_path('app/public/'.$school_code.'/posts/'.$post->id);
        if (is_dir($folder)) {
            delete_dir($folder);
        }

        $post->delete();

        return redirect()->route('posts.index');

    }

    public function delete_title_image(Post $post)
    {
        if($post->user_id != auth()->user()->id){
            return back();
        }

        $school_code = school_code();
        $file = storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/title_image.png');

        if(file_exists($file)){
            unlink($file);
        }

        $att['title_image'] = null;
        $post->update($att);

        return redirect()->route('posts.edit',$post->id);

    }

    public function delete_file(Post $post,$filename)
    {
        if($post->user_id != auth()->user()->id){
            return back();
        }

        $school_code = school_code();
        $file = storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files/'.$filename);

        if(file_exists($file)){
            unlink($file);
        }

        $att['title_image'] = null;
        $post->update($att);

        return redirect()->route('posts.edit',$post->id);

    }

    public function search(Request $request,$search=null)
    {
        $search = ($search)?$search:$request->input('search');

        if(mb_strlen($search) < 2){
            return back()->withErrors(['error'=>['必須二個字元以上']]);
        }
        $posts = Post::where('content','like','%'.$search.'%')
            ->where('title','like','%'.$search.'%')
            ->orderBy('id','DESC')
            ->paginate(20);
        $data = [
            'posts'=>$posts,
            'search'=>$search,
        ];
        return view('posts.search',$data);
    }

    public function job_title($job_title)
    {
        $posts = Post::where('job_title',$job_title)->orderBy('id','DESC')->paginate(20);
        $data = [
            'posts'=>$posts,
            'job_title'=>$job_title,
        ];
        return view('posts.job_title',$data);
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
