<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Post;
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
}
