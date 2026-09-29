<?php
$blogs = \App\Blog::orderBy('created_at','DESC')
    ->paginate(5);
?>

@can('create',\App\Post::class)
    <a href="{{ route('blogs.create') }}" class="btn btn-success btn-sm mb-3" title="新增校園部落格文章" aria-label="新增校園部落格文章">
        <i class="fas fa-plus" aria-hidden="true"></i> 新增文章
    </a>
@endcan

{{-- 無障礙結構優化：使用語意化 ul 清單替代排版用 table --}}
<ul class="list-unstyled mb-3" aria-label="最新校園部落格文章列表">
    @foreach($blogs as $blog)
        <?php
            $content = str_limit(strip_tags($blog->content), '150');
            $content = str_replace('&nbsp;', '', $content);
            
            $author = !empty($blog->job_title) 
                ? $blog->job_title 
                : ($blog->user->name == "系統管理員" ? "系統管理員" : $blog->user->title);
        ?>
        <li class="py-3 border-bottom clearfix">
            
            {{-- 1. 文章標題 --}}
            <h3 class="h6 font-weight-bold mb-2">
                <a href="{{ route('blogs.show', $blog->id) }}" class="blog-title-link" title="閱讀文章：{{ $blog->title }}">
                    {{ $blog->title }}
                </a>
            </h3>

            {{-- 2. 圖片 (文繞圖浮動) --}}
            {{-- 加上 tabindex="-1" aria-hidden="true" 避免鍵盤使用者重複 Focus 圖與字 --}}
            @if($blog->title_image)
                <a href="{{ route('blogs.show', $blog->id) }}" tabindex="-1" aria-hidden="true" class="d-block">
                    <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="blog-item-img img-fluid rounded shadow-sm" alt="{{ $blog->title }} 的封面圖片">
                </a>
            @endif

            {{-- 3. 內文摘要 (自然圍繞圖片，已改用 class) --}}
            <p class="mb-2 blog-summary-text">
                {{ $content }}
            </p>

            {{-- 4. 文章發布資訊 (作者 / 時間 / 點閱數) --}}
            <div class="blog-meta-text d-flex flex-wrap align-items-center">
                <span class="mr-3">
                    <i class="fas fa-user-circle mr-1" aria-hidden="true"></i>{{ $author }}
                </span>
                <span class="mr-3">
                    <i class="far fa-calendar-alt mr-1" aria-hidden="true"></i>{{ $blog->created_at }}
                </span>
                <span>
                    <i class="far fa-eye mr-1" aria-hidden="true"></i>點閱：{{ $blog->views }}
                </span>
            </div>

        </li>
    @endforeach
</ul>

{{-- 更多文章連結 --}}
<div class="mt-2">
    <a href="{{ route('blogs.index') }}" class="blog-title-link font-weight-bold" title="查看更多校園部落格文章" aria-label="查看更多校園部落格文章">
        <i class="far fa-hand-point-up" aria-hidden="true"></i> 更多文章...
    </a>
</div>