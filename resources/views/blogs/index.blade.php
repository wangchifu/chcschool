@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校園部落格 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校園部落格
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">文章列表</li>
                </ol>
            </nav>
            @can('create',\App\Post::class)
            <a href="{{ route('blogs.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增文章</a>
            @endcan
            <table class="table table-striped" style="word-break: break-all;">
                <tbody>
                    @foreach($blogs as $blog)
                        <tr>
                            <td width="20%">

                                @if($blog->title_image)
                                    <a href="{{ route('blogs.show',$blog->id) }}">
                                    <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="img-fluid rounded">
                                    </a>
                                @else
                                    <a href="{{ route('blogs.show',$blog->id) }}">
                                    <img src="https://picsum.photos/640/480" class="img-fluid rounded">
                                    </a>
                                    <br>隨機圖片
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('blogs.show',$blog->id) }}" style="text-decoration: none">
                                <h4>
                                    {{ $blog->title }}
                                </h4>
                                </a>
                                <?php
                                $content = str_limit(strip_tags($blog->content),'150');
                                $content = str_replace('&nbsp;','',$content);
                                ?>
                                {{ $content }}
                                <hr>
                                <p class="h6">
                                    @auth
                                        @if(auth()->user()->id == $blog->user_id)
                                        <a href="{{ route('blogs.edit',$blog->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                        @endif
                                        @if(auth()->user()->id == $blog->user_id or auth()->user()->admin ==1)
                                        <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $blog->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                                        @endif
                                    @endauth
                                    {{ Form::open(['route' => ['blogs.destroy',$blog->id], 'method' => 'DELETE','id'=>'delete'.$blog->id]) }}
                                    {{ Form::close() }}
                                    {{ $blog->user->name }} / {{ $blog->created_at }} / 點閱：{{ $blog->views }}
                                </p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $blogs->links() }}
        </div>
    </div>
@endsection
