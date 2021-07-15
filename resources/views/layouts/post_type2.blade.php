@auth
    @can('create',\App\Post::class)
        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
    @endauth
@endauth
<?php
$posts = \App\Post::where('insite',null)
    ->orderBy('top','DESC')
    ->orderBy('created_at','DESC')
    ->paginate(10);
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
        margin-right: 20px;
    }

</style>
<h4 class="text-info"><strong>一般公告</strong></h4>
<table class="table table-striped" style="word-break: break-all;">
    <tbody>
    @foreach($posts as $post)
        <tr>
            <td>
                <h4>
                    @if($post->top)
                        <p class="badge badge-danger">置頂</p>
                    @endif
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
                            <span class='text-danger'>[ 內部公告 ]</span>
                            {{ $title }}
                        @endif
                </h4>

                <?php
                $content = str_limit(strip_tags($post->content),'150');
                $content = str_replace('&nbsp;','',$content);
                ?>
                @if($post->title_image)
                    <a href="{{ route('posts.show',$post->id) }}">
                        <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" class="image2 img-fluid rounded" width="100px">
                    </a>
                @endif
                <p class="pp1">
                    {{ $content }}
                    <br>
                    <small class="text-secondary">
                        <a href="{{ route('posts.job_title',$post->job_title) }}">{{ $post->job_title }}</a> / {{ $post->created_at }} / 點閱：{{ $post->views }}
                        @if(!empty($files))
                            <span class="text-info"><i class="fas fa-file"></i> [附件]</span>
                        @endif
                    </small>
                </p>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{{ route('posts.type',0) }}"><small><i class="far fa-hand-point-up"></i> 更多 一般公告...</small></a>
<hr>
@foreach($post_types as $post_type)
    <?php
    $posts = \App\Post::where('insite',$post_type->id)
        ->orderBy('top','DESC')
        ->orderBy('created_at','DESC')
        ->paginate(10);
    ?>
<h4 class="text-info"><strong>{{ $post_type->name }}</strong></h4>
<table class="table table-striped" style="word-break: break-all;">
    <tbody>
    @foreach($posts as $post)
        <tr>
            <td>
                <h4>
                    @if($post->top)
                        <p class="badge badge-danger">置頂</p>
                    @endif
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
                        <span class='text-danger'>[ 內部公告 ]</span>
                        {{ $title }}
                    @endif
                </h4>
                <?php
                $content = str_limit(strip_tags($post->content),'150');
                $content = str_replace('&nbsp;','',$content);
                ?>
                @if($post->title_image)
                    <a href="{{ route('posts.show',$post->id) }}">
                        <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" class="image2 img-fluid rounded" width="100px">
                    </a>
                @endif
                <p class="pp1">
                    @if($can_see)
                        {{ $content }}
                    @else
                        <span>請登入後再查看</span>
                    @endif
                    <br>
                    <small class="text-secondary">
                        <a href="{{ route('posts.job_title',$post->job_title) }}">{{ $post->job_title }}</a> / {{ $post->created_at }} / 點閱：{{ $post->views }}
                        @if(!empty($files))
                            <span class="text-info"><i class="fas fa-file"></i> [附件]</span>
                        @endif
                    </small>
                </p>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>







<a href="{{ route('posts.type',$post_type->id) }}"><small><i class="far fa-hand-point-up"></i> 更多 {{ $post_type->name }}...</small></a>
<hr>
@endforeach
