@auth
    @can('create',\App\Post::class)
        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
    @endauth
@endauth
<?php
$posts = \App\Post::where('insite',null)
    ->orderBy('top','DESC')
    ->orderBy('created_at','DESC')
    ->paginate(5);
?>
<table class="table table-striped rwd-table" style="word-break:break-all;">
    <thead class="thead-light">
    <tr>
        <th nowrap width="150">
            日期
        </th>
        <th nowrap>
            標題
        </th>
        <th nowrap width="100">發佈者</th>
        <th nowrap width="80">點閱</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="4">
            <h4 class="text-info"><strong>一般公告</strong></h4>
        </td>
    </tr>
    @foreach($posts as $post)
        <tr>
            <td data-th="日期">
                @if($post->top)
                    <p class="badge badge-danger">置頂</p>
                @endif
                {{ substr($post->created_at,0,10) }}
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
        <tr>
            <td colspan="4">
                <a href="{{ route('posts.type',0) }}"><small><i class="far fa-hand-point-up"></i> 更多 一般公告...</small></a>
            </td>
        </tr>
    @endforeach
@foreach($post_types as $post_type)
    <?php
    $posts = \App\Post::where('insite',$post_type->id)
        ->orderBy('top','DESC')
        ->orderBy('created_at','DESC')
        ->paginate(5);
    ?>
    <tr>
        <td colspan="4">
            <h4 class="text-info"><strong>{{ $post_type->name }}</strong></h4>
        </td>
    </tr>
        @foreach($posts as $post)
            <tr>
                <td data-th="日期">
                    @if($post->top)
                        <p class="badge badge-danger">置頂</p>
                    @endif
                    {{ substr($post->created_at,0,10) }}
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
            <tr>
                <td colspan="4">
                    <a href="{{ route('posts.type',$post_type->id) }}"><small><i class="far fa-hand-point-up"></i> 更多 {{ $post_type->name }}...</small></a>
                </td>
            </tr>
        @endforeach
@endforeach
    </tbody>
</table>
