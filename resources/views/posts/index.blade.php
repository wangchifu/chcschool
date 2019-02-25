@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '公告系統')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>公告系統</h1>
            @auth
                @if(auth()->user()->group_id =="1")
                    <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
                @endif
            @endauth
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>日期</th>
                    <th>
                        標題
                    </th>
                    <th>發佈者</th>
                    <th>點閱</th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td width="150">
                            @if($post->top)
                                <p class="badge badge-danger">置頂</p>
                            @endif
                            {{ substr($post->created_at,0,10) }}
                        </td>
                        <td>
                            <?php
                            $title = str_limit($post->title,80);
                            //有無附件
                            $files = get_files(storage_path('app/public/posts/'.$post->id));
                            ?>
                            @if($post->insite)
                                <p class='text-danger'>[ 內部公告，請登入後瀏覽。 ]</p>
                            @else
                                <a href="{{ route('posts.show',$post->id) }}">{{ $title }}</a>
                            @endif
                            @if(!empty($files))
                                <span class="text-info"><i class="fas fa-file"></i> [附件]</span>
                            @endif
                        </td>
                        <td width="100">
                            <a href="{{ route('posts.job_title',$post->job_title) }}">{{ $post->job_title }}</a>
                        </td>
                        <td width="80">
                            {{ $post->views }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $posts->links() }}
        </div>
    </div>
@endsection
