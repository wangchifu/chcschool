<?php
    $i=1;
    if($insite=="insite"){
        $active1 = null;
        $active2 = "active";
    }
    if($insite==null){
        $active1 = "active";
        $active2 = null;
    }
?>
<h1>最新公告
@auth
    @can('create',\App\Post::class)
        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
    @endauth
@endauth
</h1>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $active1 }}" href="{{ route('index') }}">一般公告</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active2 }}" href="{{ route('insite','insite') }}">內部公告</a>
    </li>
</ul>
<table class="table table-striped" style="word-break: break-all;">
    <tbody>
    @foreach($posts as $post)
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
        $n=2;
        ?>
        <tr>
            <td>
                {{ $i }}
            </td>
            @if($can_see)
                @if($post->title_image)
                    <td width="20%">
                        <a href="{{ route('posts.show',$post->id) }}">
                            <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" class="img-fluid rounded">
                        </a>
                    </td>
                    <?php $n=1; ?>
                @endif
            @endif
            <td colspan="{{ $n }}">
                @if($can_see)
                    <h5>
                        @if($post->top)
                            <p class="badge badge-danger">置頂</p>
                        @endif
                        @if($post->insite)
                            <p class="badge badge-danger">內部公告</p>
                        @endif
                    <a href="{{ route('posts.show',$post->id) }}">{{ $post->title }}</a>
                    </h5>
                    <?php $content = str_limit($post->content,'320'); ?>
                    {{ $content }}
                    @if(!empty($files))
                        <br>
                        <span class="text-info"><i class="fas fa-file"></i> [附件]</span>
                    @endif
                    <div class="text-secondary">
                    {{ $post->job_title }} / {{ $post->created_at }} / 點閱：{{ $post->views }}
                    </div>
                @else
                    <span class='text-danger'>[ 內部公告，請登入後瀏覽。 ]</span>
                    <div class="text-secondary">
                        {{ $post->job_title }} / {{ $post->created_at }} / 點閱：{{ $post->views }}
                    </div>
                @endif
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
@if($active2 == "active")
    <a href="{{ route('posts.insite') }}"><i class="far fa-hand-point-up"></i> 更多內部公告...</a>
@elseif($active1 == "active")
    <a href="{{ route('posts.index') }}"><i class="far fa-hand-point-up"></i> 更多一般公告...</a>
@endif
