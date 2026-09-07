<?php
$inbox_posts = \App\Post::where('inbox',1)
                ->where(function ($query) {
                    $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
                })->where('created_at','<',date('Y-m-d H:i:s'))
                ->orderBy('created_at','DESC')
                ->get();
?>
<div class="table-responsive">
    {{-- 無障礙表格結構修正：加入 aria-label 描述表格用途 --}}
    <table class="table table-striped" style="word-break:break-all;" aria-label="常駐公告列表">
        <thead class="thead-light">
        <tr>
            {{-- 無障礙 CS2140401C 修正：將 120px, 100px, 250px, 80px 全數改為 rem 相對單位 --}}
            <th scope="col" class="text-nowrap" style="width: 7.5rem;">
                日期
            </th>
            <th scope="col" class="text-nowrap" style="width: 6.25rem;">
                類別
            </th>
            <th scope="col" class="text-nowrap" style="min-width: 15.625rem;">
                標題
            </th>
            <th scope="col" class="text-nowrap" style="width: 6.25rem;">發佈者</th>
            <th scope="col" class="text-nowrap" style="width: 5rem;">點閱</th>
        </tr>
        </thead>
        <tbody>
        @foreach($inbox_posts as $post)
        <tr>
            <td>                
                {{ substr($post->created_at,0,10) }}
            </td>
            <td>
                <?php
                    $insite = ($post->insite != null)?$post->insite:0;
                ?>
                {{ $post_type_array[$insite] }}
            </td>
            <td>
                @if($post->top)
                    <span class="badge badge-danger">置頂</span>
                @endif
                @if($post->inbox)
                    <span class="badge badge-warning">常駐</span>
                @endif
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

                {{-- 無障礙 HM1240401C 修正：補上完整標題提示說明 --}}
                @if($can_see)
                    <a href="{{ route('posts.show',$post->id) }}" title="閱讀公告：{{ $post->title }}" aria-label="閱讀公告：{{ $post->title }}">{{ $title }}</a>
                @else
                    {{ $title }}
                @endif

                {{-- 無障礙 1.1.1 修正：圖示補上 aria-hidden 與隱藏朗讀文字 --}}
                @if(!empty($photos))
                    <span class="text-success ml-1" title="附有圖片檔">
                        <i class="fas fa-image" aria-hidden="true"></i>
                        <span class="sr-only">（附有圖片檔）</span>
                    </span>
                @endif
                @if(!empty($files))
                    <span class="text-info ml-1" title="附有附件下載">
                        <i class="fas fa-download" aria-hidden="true"></i>
                        <span class="sr-only">（附有附件下載）</span>
                    </span>
                @endif
            </td>
            <td>
                {{-- 無障礙 HM1240401C 修正：補上 title 與 aria-label --}}
                <a href="{{ route('posts.job_title',$post->job_title) }}" title="查看發佈者 {{ $post->job_title }} 的所有公告" aria-label="查看發佈者 {{ $post->job_title }} 的所有公告">{{ $post->job_title }}</a>
            </td>
            <td>
                {{ $post->views }}
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>