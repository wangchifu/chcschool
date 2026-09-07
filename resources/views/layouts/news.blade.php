@auth
    @can('create',\App\Post::class)
        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus" aria-hidden="true"></i> 新增公告
        </a>
    @else
        <?php 
            $users = \App\User::where('admin',1)->whereNull('disable')->get();
            $user_name = '';
            foreach($users as $user){
                $user_name .= $user->title ." ".$user->name.'\n\r';
            }
            $msg = '請\n\r'.$user_name.'加你進去校網行政人員群組';
        ?>
        {{-- 無障礙 HM1020401C 修正：將 href="#!" 的虛設連結改為語意明確的 button 按鈕 --}}
        <button type="button" class="btn btn-success btn-sm" onclick="alert('{{ $msg }}')">
            <i class="fas fa-plus" aria-hidden="true"></i> 我想公告
        </button>        
    @endcan
@endauth

<table class="table table-striped mt-2" style="word-break: break-all;" aria-label="最新公告列表">
    <caption class="sr-only">最新公告列表說明</caption>
    <thead>
        <tr>
            <th scope="col" style="width: 5%;">編號</th>
            <th scope="col" style="width: 20%;">公告縮圖</th>
            <th scope="col" style="width: 75%;">公告標題與內容摘要</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1; ?>
    @foreach($posts as $post)
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
        $has_thumbnail = ($can_see && $post->title_image);
        ?>
        <tr>
            <th scope="row" class="text-center">{{ $i }}</th>
            
            @if($can_see && $post->title_image)
                <td>
                    <a href="{{ route('posts.show',$post->id) }}" aria-label="查看公告：{{ $post->title }}">
                        {{-- 無障礙 HM1240401C 修正：將 alt 描述修改為具代表性的標題文字 --}}
                        <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" class="img-fluid rounded" alt="公告縮圖：{{ $post->title }}">
                    </a>
                </td>
            @else
                <td></td>
            @endif

            <td colspan="{{ $has_thumbnail ? 1 : 2 }}">
                @if($can_see)
                    <div style="font-size: 1.25rem;">
                        @if($post->top)
                            <span class="badge badge-danger">置頂</span>
                        @endif
                        @if($post->inbox)
                            <span class="badge badge-warning">常駐</span>
                        @endif
                        @if($post->insite==1)
                            <span class="badge badge-danger">內部公告</span>
                        @endif
                        <a href="{{ route('posts.show',$post->id) }}" class="font-weight-bold text-primary">{{ $post->title }}</a>
                    </div>
                    
                    <?php
                        $content = str_limit(strip_tags($post->content),'320');
                        $content = str_replace('&nbsp;','',$content);
                    ?>
                    <p class="mb-1 text-dark">{{ $content }}</p>

                    @if(!empty($photos))
                        {{-- 無障礙 HM1120201C 修正：加上相應的無障礙提示文字，避免純圖示無朗讀資訊 --}}
                        <span class="text-success mr-2">
                            <i class="fas fa-images" aria-hidden="true"></i>
                            <span class="sr-only">（含有照片附件）</span>
                        </span>
                    @endif
                    @if(!empty($files))
                        <span class="text-info mr-2">
                            <i class="fas fa-download" aria-hidden="true"></i>
                            <span class="sr-only">（含有檔案下載）</span>
                        </span>
                    @endif

                    <div class="text-secondary small mt-1">
                        @if($post->insite==null)
                            一般公告 / {{ $post->job_title }} / {{ $post->created_at }} / 點閱：{{ $post->views }}
                        @else
                            {{ $post_type_array[$post->insite] }} / {{ $post->job_title }} / {{ $post->created_at }} / 點閱：{{ $post->views }}
                        @endif
                    </div>
                @else
                    <span class="text-danger font-weight-bold">[ 內部公告 ]</span>
                    <span style="font-size: 1.25rem;" class="font-weight-bold">{{ $title }}</span>
                    <div class="text-secondary small mt-1">
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

<div class="mt-2">
    <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="far fa-hand-point-up" aria-hidden="true"></i> 查看更多公告...
    </a>
</div>