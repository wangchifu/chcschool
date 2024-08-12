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
        margin-right: 20px;
    }

</style>
@can('create',\App\Post::class)
    <a href="{{ route('blogs.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增文章</a>
@endcan
<table class="table table-striped" style="word-break: break-all;">
    <tbody>
    @foreach($blogs as $blog)
        <tr>
            <td>
                <a href="{{ route('blogs.show',$blog->id) }}" style="text-decoration: none">
                    {{ $blog->title }}
                </a>
                <?php
                $content = str_limit(strip_tags($blog->content),'150');
                $content = str_replace('&nbsp;','',$content);
                ?>
                @if($blog->title_image)
                    <a href="{{ route('blogs.show',$blog->id) }}">
                        <img src="{{ asset('storage/'.$school_code.'/blogs/'.$blog->id.'/title_image.png') }}" class="image2 img-fluid rounded" width="100px">
                    </a>
                @endif
                <p class="pp1">
                    {{ $content }}
                    <br>
                    <small class="text-secondary">
                        @if(!empty($blog->job_title))
                            {{ $blog->job_title }}
                        @else
                            @if($blog->user->name == "系統管理員")
                                系統管理員
                            @else
                                {{ substr_cut_name($blog->user->name) }}
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
<small><a href="{{ route('blogs.index') }}"><i class="far fa-hand-point-up"></i> 更多 文章...</a></small>
