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

<table class="table table-hover align-middle mt-2" style="word-break: break-all;" aria-label="最新公告列表">
    <caption class="sr-only">最新公告列表說明</caption>
    <thead>
        <tr>
            <th scope="col" style="width: 8%;" class="text-center">編號</th>
            <th scope="col" style="width: 92%;">公告內容</th>
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
        ?>
        <tr>
            <!-- 編號欄 -->
            <th scope="row" class="text-center align-top pt-3">{{ $i }}</th>
            
            <!-- 內容與縮圖整合欄 -->
            <td>
                <div class="d-flex flex-column flex-md-row align-items-start py-1">
                    
                    {{-- 有縮圖時置左呈現 --}}
                    @if($can_see && $post->title_image)
                        <div class="mr-md-3 mb-2 mb-md-0 flex-shrink-0" style="width: 120px;">
                            <a href="{{ route('posts.show',$post->id) }}" class="d-block" aria-label="查看公告：{{ $post->title }}">
                                <img src="{{ asset('storage/'.$school_code.'/posts/'.$post->id.'/title_image.png') }}" class="img-fluid rounded border shadow-sm" alt="公告縮圖：{{ $post->title }}" style="object-fit: cover; height: 80px; width: 100%;">
                            </a>
                        </div>
                    @endif

                    {{-- 文字主要內容 --}}
                    <div class="flex-grow-1">
                        @if($can_see)
                            <div style="font-size: 1.15rem;" class="mb-1">
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
                                $content = str_limit(strip_tags($post->content),'250');
                                $content = str_replace('&nbsp;','',$content);
                            ?>
                            <p class="mb-1 text-secondary small">{{ $content }}</p>

                            <div class="d-flex flex-wrap align-items-center text-muted small mt-1">
                                <span class="mr-3">
                                    @if($post->insite==null)
                                        <i class="far fa-folder mr-1" aria-hidden="true"></i>一般公告
                                    @else
                                        <i class="far fa-folder mr-1" aria-hidden="true"></i>{{ $post_type_array[$post->insite] }}
                                    @endif
                                </span>
                                <span class="mr-3"><i class="far fa-user mr-1" aria-hidden="true"></i>{{ $post->job_title }}</span>
                                <span class="mr-3"><i class="far fa-clock mr-1" aria-hidden="true"></i>{{ substr($post->created_at,0,10) }}</span>
                                <span class="mr-3"><i class="far fa-eye mr-1" aria-hidden="true"></i>{{ $post->views }}</span>

                                @if(!empty($photos))
                                    <span class="text-success mr-2" title="含有照片附件">
                                        <i class="fas fa-images" aria-hidden="true"></i>
                                        <span class="sr-only">（含有照片附件）</span>
                                    </span>
                                @endif
                                @if(!empty($files))
                                    <span class="text-info mr-2" title="含有檔案下載">
                                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                                        <span class="sr-only">（含有檔案下載）</span>
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="mb-1">
                                <span class="badge badge-danger">[ 內部公告 ]</span>
                                <span style="font-size: 1.15rem;" class="font-weight-bold text-secondary">{{ $title }}</span>
                            </div>
                            <div class="text-muted small mt-1">
                                <span class="mr-3"><i class="far fa-user mr-1" aria-hidden="true"></i>{{ $post->job_title }}</span>
                                <span class="mr-3"><i class="far fa-clock mr-1" aria-hidden="true"></i>{{ substr($post->created_at,0,10) }}</span>
                            </div>
                        @endif
                    </div>

                </div>
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