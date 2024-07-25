<?php
$inbox_posts = \App\Post::where('inbox',1)
                ->where(function ($query) {
                    $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
                })->where('created_at','<',date('Y-m-d H:i:s'))
                ->orderBy('created_at','DESC')
                ->get();
?>
<table class="table table-striped rwd-table" style="word-break:break-all;">
    <thead class="thead-light">
    <tr>
        <th nowrap width="200px">
            日期
        </th>
        <th width="100px">
            類別
        </th>
        <th nowrap>
            標題
        </th>
        <th nowrap width="100px">發佈者</th>
        <th nowrap width="80px">點閱</th>
    </tr>
    </thead>
    <tbody>
    @foreach($inbox_posts as $post)
    <tr>
        <td data-th="日期">
            @if($post->top)
                <p class="badge badge-danger">置頂</p>
            @endif
            {{ substr($post->created_at,0,10) }}
        </td>
        <td data-th="類別">
            <?php
                $insite = ($post->insite != null)?$post->insite:0;
            ?>
            {{ $post_type_array[$insite] }}
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
            $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
            ?>
            @if($post->insite==1)
                <span class="text-danger">[ 內部公告 ]</span>
            @endif
            @if($can_see)
                <a href="{{ route('posts.show',$post->id) }}">{{ $title }}</a>
            @else

                {{ $title }}
            @endif
            @if(!empty($photos))
                <span class="text-success"><i class="fas fa-image"></i></span>
            @endif
            @if(!empty($files))
                <span class="text-info"><i class="fas fa-download"></i></span>
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
