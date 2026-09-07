<?php
$blogs = \App\Blog::orderBy('created_at','DESC')
    ->paginate(5);
?>
<style>
    .image-container{
        max-width: 90%;
        margin: 0 5%;
    }

    .image1{
        float: right;
    }

    .image2{
        float: left;
        margin-right: 1.25rem; /* 無障礙 CS2140401C：將 20px 改為相對單位 1.25rem */
    }
</style>

@can('create',\App\Post::class)
    <a href="{{ route('blogs.create') }}" class="btn btn-success btn-sm mb-2" title="新增校園部落格文章" aria-label="新增校園部落格文章">
        <i class="fas fa-plus" aria-hidden="true"></i> 新增文章
    </a>
@endcan

{{-- 無障礙表格修復：加入 aria-label 標示表格用途 --}}
<table class="table table-striped" style="word-break: break-all;" aria-label="最新校園部落格文章列表">
    <tbody>
    @foreach($blogs as $blog)
        <tr>
            <td>
                {{-- 文章標題連結 --}}
                <a href="{{ route('blogs.show',$blog->id) }}" style="text-decoration: none" title="閱讀文章：{{ $blog->title }}" aria-label="閱讀文章：{{ $blog->title }}">
                    <strong>{{ $blog->title }}</strong>
                </a>
                
                <?php
                $content = str_limit(strip_tags($blog->content),'150');
                $content = str_replace('&nbsp;','',$content);
                ?>
                
                {{-- 圖片連結與替代文字修復 --}}
                @if($blog->title_image)
                    <a href="{{ route('blogs.show',$blog->id) }}" title="閱讀文章：{{ $blog->title }}" aria-label="閱讀文章：{{ $blog->title }}">
                        <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="image2 img-fluid rounded" style="max-width: 6.25rem; height: auto;" alt="{{ $blog->title }} 的封面圖片">
                    </a>
                @endif

                <p class="pp1 mt-2">
                    {{ $content }}
                    <br>
                    <small class="text-secondary">
                        @if(!empty($blog->job_title))
                            {{ $blog->job_title }}
                        @else
                            @if($blog->user->name == "系統管理員")
                                系統管理員
                            @else
                                {{ $blog->user->title }}
                            @endif
                        @endif                                        
                         / {{ $blog->created_at }} / 點閱：{{ $blog->views }}
                    </small>
                </p>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- 更多文章連結修復 --}}
<small>
    <a href="{{ route('blogs.index') }}" title="查看更多校園部落格文章" aria-label="查看更多校園部落格文章">
        <i class="far fa-hand-point-up" aria-hidden="true"></i> 更多 文章...
    </a>
</small>