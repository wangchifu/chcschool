<?php
    $key = rand(100,999);
    session(['search' => $key]);
?>
<div align="right">
    <form action="{{ route('posts.search') }}" method="post" class="search-form" id="this_form">
        {{ csrf_field() }}
        <table>
            <tr>
                <td>
                    <input type="text" name="search" id="search" placeholder="搜尋公告標題或內文" required>
                </td>
                <td>
                    <input type="text" name="check" placeholder="請輸入驗證碼：{{ session('search') }}" required maxlength="3">
                </td>
                <td>
                    <button><i class="fas fa-search"></i></button>
                </td>
            </tr>
        </table>
        @include('layouts.errors')
    </form>
</div>
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
                {{ substr($post->created_at,0,10) }}
            </td>
            <td>
                @if($post->insite == null)
                    <a href="{{ route('posts.type',0) }}">一般公告</a>
                @else
                    <a href="{{ route('posts.type',$post->insite) }}">{{ $post_types[$post->insite] }}</a>
                @endif
            </td>
            <td data-th="標題">
                <?php
                if($post->insite==1){
                    if(auth()->check()){
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
                ?>
                @if($can_see)
                    @if($post->insite==1)
                        <span class="text-danger">[ 內部公告 ]</span>
                    @endif
                    <a href="{{ route('posts.show',$post->id) }}">{{ $title }}</a>
                @else
                    <span class='text-danger'>[ 內部公告，請登入後瀏覽。 ]</span>
                @endif
                @if(!empty($files))
                    <span class="text-info"><i class="fas fa-file"></i> [附件]</span>
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
<script>
    var validator = $("#this_form").validate();
</script>
