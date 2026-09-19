@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', $blog->title.' | ')

@section('in_head')
    <link rel="stylesheet" href="{{ asset('venobox/venobox.min.css') }}" type="text/css" media="screen">
    <script src="{{ asset('venobox/venobox.min.js') }}"></script>
    <style>
        .blog-image-container {
            max-width: 100%;
        }

        /* 圖片文繞圖與無障礙相對單位 */
        .blog-title-img {
            float: left;
            margin-right: 1.25rem;
            margin-bottom: 0.75rem;
            max-width: 40%;
            height: auto;
        }

        .blog-show-title {
            color: #002244;
        }

        /* 詮釋資料對比度修正 (對比度 > 4.5:1) */
        .blog-meta-info {
            color: #495057 !important;
            font-size: 0.9rem;
        }

        /* 文章內文樣式 */
        .blog-content-body {
            color: #212529;
            line-height: 1.8;
            font-size: 1.05rem;
            word-break: break-all;
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
                    <li class="breadcrumb-item">
                        <a href="{{ route('blogs.index') }}" style="color: #004085; text-decoration: underline;">文章列表</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #495057;">{{ $blog->title }}</li>
                </ol>
            </nav>

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-9">
                    <article class="card shadow-sm border-light">
                        
                        {{-- 文章標題與發布資訊區 --}}
                        <div class="card-header bg-white border-bottom py-3">
                            <h2 class="h3 font-weight-bold blog-show-title mb-2">
                                {{ $blog->title }}
                            </h2>

                            <div class="d-flex flex-wrap align-items-center justify-content-between pt-1">
                                <div class="blog-meta-info my-1">
                                    <?php
                                        $author = !empty($blog->job_title) 
                                            ? $blog->job_title 
                                            : ($blog->user->name == "系統管理員" ? "系統管理員" : $blog->user->title);
                                    ?>
                                    <span class="mr-3"><i class="fas fa-user-circle mr-1" aria-hidden="true"></i>{{ $author }}</span>
                                    <span class="mr-3"><i class="far fa-calendar-alt mr-1" aria-hidden="true"></i>{{ $blog->created_at }}</span>
                                    <span><i class="far fa-eye mr-1" aria-hidden="true"></i>點閱：{{ $blog->views }}</span>
                                </div>

                                {{-- 管理操作按鈕組 --}}
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

                        {{-- 文章內容區 --}}
                        <div class="card-body py-4">
                            <div class="blog-image-container clearfix">
                                @if($blog->title_image)
                                    <a href="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="venobox d-inline-block" data-gall="gall1" title="放大檢視封面圖片：{{ $blog->title }}" aria-label="放大檢視封面圖片：{{ $blog->title }}">
                                        <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="blog-title-img img-fluid rounded border shadow-sm" alt="{{ $blog->title }} 的封面圖片">
                                    </a>
                                @endif

                                {{-- 文章主要內容 (帶入無障礙與字體清理函式) --}}
                                <div class="blog-content-body table-responsive">
                                    {!! enhance_content_accessibility(fix_empty_links(clean_font_size_units($blog->content))) !!}
                                </div>
                            </div>
                        </div>

                        {{-- 上下篇導覽 --}}
                        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                            @if($last_id)
                                <a href="{{ route('blogs.show', $last_id) }}" class="btn btn-secondary btn-sm" title="前往上一篇文章" aria-label="前往上一篇文章">
                                    <i class="fas fa-arrow-alt-circle-left mr-1" aria-hidden="true"></i> 上一篇文章
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary btn-sm" disabled aria-disabled="true">
                                    <i class="fas fa-arrow-alt-circle-left mr-1" aria-hidden="true"></i> 上一篇文章
                                </button>
                            @endif

                            @if($next_id)
                                <a href="{{ route('blogs.show', $next_id) }}" class="btn btn-secondary btn-sm" title="前往下一篇文章" aria-label="前往下一篇文章">
                                    下一篇文章 <i class="fas fa-arrow-alt-circle-right ml-1" aria-hidden="true"></i>
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary btn-sm" disabled aria-disabled="true">
                                    下一篇文章 <i class="fas fa-arrow-alt-circle-right ml-1" aria-hidden="true"></i>
                                </button>
                            @endif
                        </div>

                    </article>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var vb = new VenoBox({
            selector: '.venobox',
            numeration: true,
            infinigall: true,
            spinner: 'rotating-plane'
        });

        $(document).on('click', '.vbox-close', function() {
            vb.close();
        });
    });
</script>
@endsection