@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校園部落格 | ')

@section('in_head')
    <style>
        /* 標題連結色彩對比增強 (對比度 > 7:1) */
        .blog-index-title {
            color: #004085 !important;
            text-decoration: underline;
        }
        .blog-index-title:hover,
        .blog-index-title:focus {
            color: #002752 !important;
        }

        /* 詮釋資料文字對比修正 (取代次要文字，確保 > 4.5:1) */
        .blog-meta-info {
            color: #495057 !important;
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1 class="h2 font-weight-bold mb-3" style="color: #002244;">校園部落格</h1>

            {{-- 導覽麵包屑 --}}
            <nav aria-label="麵包屑導覽">
                <ol class="breadcrumb bg-light border">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}" style="color: #004085; text-decoration: underline;">首頁</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #495057;">文章列表</li>
                </ol>
            </nav>

            {{-- 新增文章按鈕 --}}
            @can('create', \App\Post::class)
                <div class="mb-3">
                    <a href="{{ route('blogs.create') }}" class="btn btn-success btn-sm" title="新增校園部落格文章" aria-label="新增校園部落格文章">
                        <i class="fas fa-plus" aria-hidden="true"></i> 新增文章
                    </a>
                </div>
            @endcan

            {{-- 無障礙結構優化：採用語意化 ul 列表取代 table --}}
            <ul class="list-unstyled mb-4" aria-label="校園部落格文章列表">
                @foreach($blogs as $blog)
                    <?php
                        $content = str_limit(strip_tags($blog->content), '150');
                        $content = str_replace('&nbsp;', '', $content);
                        $author = !empty($blog->job_title) 
                            ? $blog->job_title 
                            : ($blog->user->name == "系統管理員" ? "系統管理員" : $blog->user->title);
                    ?>
                    <li class="card mb-3 border-light shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                
                                {{-- 圖片區域 (小螢幕滿寬，大螢幕佔 3 欄) --}}
                                <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
                                    {{-- 圖片加 tabindex="-1" aria-hidden="true" 避免鍵盤 Focus 兩次 --}}
                                    @if($blog->title_image)
                                        <a href="{{ route('blogs.show', $blog->id) }}" tabindex="-1" aria-hidden="true">
                                            <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="img-fluid rounded border w-100" style="max-height: 180px; object-fit: cover;" alt="{{ $blog->title }} 的封面圖片">
                                        </a>
                                    @else
                                        <a href="{{ route('blogs.show', $blog->id) }}" tabindex="-1" aria-hidden="true">
                                            <img src="https://picsum.photos/640/480" class="img-fluid rounded border w-100" style="max-height: 180px; object-fit: cover;" alt="{{ $blog->title }} 的預設示意圖片">
                                        </a>
                                    @endif
                                </div>

                                {{-- 文字與操作區域 --}}
                                <div class="col-md-8 col-lg-9">
                                    {{-- 文章標題 --}}
                                    <h2 class="h4 font-weight-bold mb-2">
                                        <a href="{{ route('blogs.show', $blog->id) }}" class="blog-index-title" title="閱讀文章：{{ $blog->title }}">
                                            {{ $blog->title }}
                                        </a>
                                    </h2>

                                    {{-- 內容摘要 --}}
                                    <p class="mb-3" style="color: #212529; line-height: 1.6; word-break: break-all;">
                                        {{ $content }}
                                    </p>

                                    <hr class="my-2">

                                    {{-- 底欄：文章資訊與管理按鈕 --}}
                                    <div class="d-flex flex-wrap align-items-center justify-content-between pt-1">
                                        <div class="blog-meta-info my-1">
                                            <span class="mr-3"><i class="fas fa-user-circle mr-1" aria-hidden="true"></i>{{ $author }}</span>
                                            <span class="mr-3"><i class="far fa-calendar-alt mr-1" aria-hidden="true"></i>{{ $blog->created_at }}</span>
                                            <span><i class="far fa-eye mr-1" aria-hidden="true"></i>點閱：{{ $blog->views }}</span>
                                        </div>

                                        {{-- 管理權限按鈕組 --}}
                                        @auth
                                            <div class="my-1">
                                                @if(auth()->user()->id == $blog->user_id)
                                                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-outline-primary btn-sm mr-1" title="修改文章：{{ $blog->title }}" aria-label="修改文章：{{ $blog->title }}">
                                                        <i class="fas fa-edit" aria-hidden="true"></i> 修改
                                                    </a>
                                                @endif

                                                @if(auth()->user()->id == $blog->user_id || auth()->user()->admin == 1)
                                                    {!! Form::open(['route' => ['blogs.destroy', $blog->id], 'method' => 'DELETE', 'id' => 'delete'.$blog->id, 'class' => 'd-inline']) !!}
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('確定要刪除「{{ addslashes($blog->title) }}」這篇文章嗎？');" title="刪除文章：{{ $blog->title }}" aria-label="刪除文章：{{ $blog->title }}">
                                                            <i class="fas fa-trash" aria-hidden="true"></i> 刪除
                                                        </button>
                                                    {!! Form::close() !!}
                                                @endif
                                            </div>
                                        @endauth
                                    </div>

                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- 分頁控制區 --}}
            <div class="d-flex justify-content-center">
                {{ $blogs->links() }}
            </div>
        </div>
    </div>
@endsection