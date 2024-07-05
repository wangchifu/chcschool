@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '我的公告 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>公告系統</h1>
            @can('create',\App\Post::class)
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('posts.index') }}">架上公告</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('posts.index_my') }}">我的公告</a>
                </li>
            </ul>
            @endcan
            <br>
            <table class="table table-striped rwd-table" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th nowrap>日期
                        @can('create',\App\Post::class)
                            <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
                        @endauth
                    </th>
                    <th>
                        類別
                    </th>
                    <th nowrap>
                        標題
                    </th>
                    <th nowrap>發佈者</th>
                    <th nowrap>點閱</th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td data-th="日期">
                            @if($post->top)
                                <p class="badge badge-danger">置頂</p>
                            @endif
                            @if($post->inbox)
                                <p class="badge badge-warning">常駐</p>
                            @endif
                            {{ substr($post->created_at,0,10) }}
                            @if($post->created_at > date('Y-m-d H:i:s'))
                            <h5><span class="badge badge-danger">尚未上架<br>{{ $post->created_at }}</span></h5>
                            @endif
                            @if($post->die_date < date('Y-m-d') and $post->die_date != null)
                            <h5><span class="badge badge-dark">已經下架<br>{{ $post->die_date  }}</span></h5>
                            @endif
                            @if($post->die_date > date('Y-m-d') and $post->die_date != null)
                            <h5><span class="badge badge-warning">下架日期<br>{{ $post->die_date  }}</span></h5>
                            @endif
                        </td>
                        <td data-th="類別">
                            @if($post->insite == null)
                                <a href="{{ route('posts.type',0) }}">一般公告</a>
                            @else
                                <a href="{{ route('posts.type',$post->insite) }}">{{ $post_types[$post->insite] }}</a>
                            @endif
                        </td>
                        <td data-th="標題">
                            <?php
                            if($post->insite==1){
                                if(auth()->check() or check_ip()){
                                    $can_see = 1;
                                }else{
                                    $can_see = 0;
                                }
                            }else{
                                $can_see = 1;
                            };
                            $school_code = school_code();
                            $title = str_limit($post->title,80);
                            //有無附件
                            $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                            $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                            ?>
                            @if($can_see)
                                @if($post->insite==1)
                                    <span class="text-danger">[ 內部公告 ]</span>
                                @endif
                                <a href="{{ route('posts.show',$post->id) }}">{{ $title }}</a>
                            @else
                    <span class='text-danger'>[ 內部公告 ]</span>
                                {{ $title  }}
                            @endif
                            @if(!empty($photos))
                                <span class="text-success"><i class="fas fa-image"></i></span>
                            @endif
                            @if(!empty($files))
                                <span class="text-info"> <i class="fas fa-download"></i></span>
                            @endif
                        </td>
                        <td data-th="發佈者">
                            <a href="{{ route('posts.job_title',$post->job_title) }}">{{ $post->job_title }}</a>
                        </td>
                        <td data-th="點閱">
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
