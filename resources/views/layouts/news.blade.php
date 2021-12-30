@auth
    @can('create',\App\Post::class)
        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
    @endcan
@endauth
<table class="table table-striped" style="word-break: break-all;">
    <tbody>
    <?php $i=1; ?>
    @foreach($posts as $post)
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
                        @if($post->insite==1)
                            <p class="badge badge-danger">內部公告</p>
                        @endif
                    <a href="{{ route('posts.show',$post->id) }}">{{ $post->title }}</a>
                    </h5>
                    <?php
                        $content = str_limit(strip_tags($post->content),'320');
                        $content = str_replace('&nbsp;','',$content);
                    ?>
                    {{ $content }}
                    @if(!empty($files))
                        <br>
                        <span class="text-info"><i class="fas fa-file"></i> [附件]</span>
                    @endif
                    <div class="text-secondary">
                        @if($post->insite==null)
                            一般公告 / {{ $post->job_title }} / {{ $post->created_at }} / 點閱：{{ $post->views }}
                        @else
                            {{ $post_type_array[$post->insite] }} / {{ $post->job_title }} / {{ $post->created_at }} / 點閱：{{ $post->views }}
                        @endif
                    </div>
                @else
		    <span class='text-danger'>[ 內部公告 ]</span>
                    <h5>
                        {{ $title }}
                    </h5>
                    <div class="text-secondary">
                        @if($post->insite==null)
                            一般公告 / {{ $post->job_title }} / {{ $post->created_at }} / 點閱：{{ $post->views }}
                        @else
                            {{ $post_type_array[$post->insite] }} / {{ $post->job_title }} / {{ $post->created_at }} / 點閱：{{ $post->views }}
                        @endif
                    </div>
                @endif
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
    </tbody>
</table>
<small><a href="{{ route('posts.index') }}"><i class="far fa-hand-point-up"></i> 更多 公告...</a></small>
