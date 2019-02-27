<div align="right">
    <form action="{{ route('posts.search') }}" method="post" class="search-form" id="search_form">
        {{ csrf_field() }}
        <table>
            <tr>
                <td>
                    <input type="text" name="search" id="search" placeholder="搜尋公告標題或內文">
                </td>
                <td>
                    <button><i class="fas fa-search"></i></button>
                </td>
            </tr>
        </table>
        @include('layouts.errors')
    </form>
</div>
<table class="table table-striped" style="word-break:break-all;">
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
                if($post->insite){
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
                    @if($post->insite)
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
