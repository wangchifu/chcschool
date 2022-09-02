<?php
$posts = \App\Post::where('insite',null)
    ->orderBy('top','DESC')
    ->orderBy('created_at','DESC')
    ->paginate(10);
?>

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#post_type_home" role="tab" aria-controls="home" aria-selected="true">一般公告</a>
    </li>
    <?php
    $p=1;
    ?>
    @foreach($post_types as $post_type)
        <?php
        $p++;
        ?>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#post_type_profile{{ $p }}" role="tab" aria-controls="profile" aria-selected="false">{{ $post_type->name }}</a>
        </li>
    @endforeach
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="post_type_home" role="tabpanel" aria-labelledby="home-tab" style="margin: 10px;">
        @auth
            @can('create',\App\Post::class)
                <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
            @endauth
        @endauth
        <table class="table table-striped rwd-table" style="word-break:break-all;">
            <thead class="thead-light">
            <tr>
                <th nowrap width="160">
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
                            if(auth()->check() or check_ip()){
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
        </table>
        <a href="{{ route('posts.type',0) }}"><small><i class="far fa-hand-point-up"></i> 更多 一般公告...</small></a>
    </div>

    <?php
    $p=2;
    ?>

    @foreach($post_types as $post_type)
        <div class="tab-pane fade" id="post_type_profile{{ $p }}" role="tabpanel" aria-labelledby="profile-tab" style="margin: 10px;">
            <?php
            $p++;
            $posts = \App\Post::where('insite',$post_type->id)
                ->orderBy('top','DESC')
                ->orderBy('created_at','DESC')
                ->paginate(10);
            ?>
            @auth
                @can('create',\App\Post::class)
                    <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
                @endcan
            @endauth
            <table class="table table-striped rwd-table" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th nowrap width="160">
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
                                    {{ $title }}
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
            <a href="{{ route('posts.type',$post_type->id) }}"><small><i class="far fa-hand-point-up"></i> 更多 {{ $post_type->name }}...</small></a>
        </div>
    @endforeach
</div>
