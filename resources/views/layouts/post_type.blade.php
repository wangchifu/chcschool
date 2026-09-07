<style>
    /* 無障礙 HM1020401C 修正：頁籤與按鈕 Focus 高對比視覺提示 */
    .nav-tabs .nav-link:focus-visible,
    .table a:focus-visible,
    button:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
    }
</style>

<!-- 無障礙頁籤導覽列 -->
<ul class="nav nav-tabs" id="postTab" role="tablist" aria-label="公告分類頁籤">
    @if($setup->all_post)
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="post_type_all_post-tab" data-toggle="tab" href="#post_type_all_post" role="tab" aria-controls="post_type_all_post" aria-selected="true">全部公告</a>
    </li>
    @endif
    <?php $p=1; ?>
    @foreach($post_types as $post_type)
        <?php
        $active = ($p==1 and $setup->all_post==null)?"active":null;  
        $aria_selected = ($p==1 and $setup->all_post==null)?"true":"false";  
        ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $active }}" id="post_type_profile{{ $p }}-tab" data-toggle="tab" href="#post_type_profile{{ $p }}" role="tab" aria-controls="post_type_profile{{ $p }}" aria-selected="{{ $aria_selected }}">{{ $post_type->name }}</a>
        </li>
        <?php $p++; ?>
    @endforeach
</ul>

<!-- 頁籤內容區塊 -->
<div class="tab-content" id="postTabContent">
    @if ($setup->all_post==1)
        <div class="tab-pane fade show active p-2" id="post_type_all_post" role="tabpanel" aria-labelledby="post_type_all_post-tab">
            @auth
                @can('create',\App\Post::class)
                    <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm mb-2">
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
                    {{-- 無障礙 HM1020401C 修正：改用 button 元素取代 href="#!" --}}
                    <button type="button" class="btn btn-success btn-sm mb-2" onclick="alert('{{ $msg }}')">
                        <i class="fas fa-plus" aria-hidden="true"></i> 我想公告
                    </button>
                @endcan
            @endauth

            <div class="table-responsive">
                <table class="table table-striped" style="word-break:break-all;" aria-label="全部公告列表">
                    <caption class="sr-only">全部公告表格列表</caption>
                    <thead class="thead-light">
                    <tr>
                        <th scope="col" style="width: 120px;">日期</th>
                        <th scope="col" style="width: 100px;">類別</th>
                        <th scope="col" style="min-width: 250px;">標題</th>
                        <th scope="col" style="width: 120px;">發佈者</th>
                        <th scope="col" style="width: 80px;">點閱</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ substr($post->created_at,0,10) }}</td>
                                <td>
                                    <?php $insite = ($post->insite != null)?$post->insite:0; ?>
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
                                        $can_see = (auth()->check() or check_ip()) ? 1 : 0;
                                    }else{
                                        $can_see = 1;
                                    };
                                    $school_code = school_code();                                
                                    $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                                    $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                                    ?>
                                    @if($post->insite==1)
                                        <span class="text-danger font-weight-bold">[ 內部公告 ]</span>
                                    @endif
                                    
                                    @if($can_see)
                                        <a href="{{ route('posts.show',$post->id) }}" class="font-weight-bold">{{ $post->title }}</a>
                                    @else                    
                                        <span>{{ $post->title }}</span>
                                    @endif

                                    {{-- 無障礙 HM1120201C 修正：補充隱藏式文字說明與 aria-hidden --}}
                                    @if(!empty($photos))
                                        <span class="text-success ml-1">
                                            <i class="fas fa-image" aria-hidden="true"></i>
                                            <span class="sr-only">（含圖片附件）</span>
                                        </span>
                                    @endif
                                    @if(!empty($files))
                                        <span class="text-info ml-1">
                                            <i class="fas fa-download" aria-hidden="true"></i>
                                            <span class="sr-only">（含檔案下載）</span>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('posts.job_title',$post->job_title) }}">{{ $post->job_title }}</a>
                                </td>
                                <td>{{ $post->views }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('posts.index') }}" class="btn btn-link text-primary btn-sm">
                <i class="far fa-hand-point-up" aria-hidden="true"></i> 查看更多公告...
            </a>
        </div>
    @endif

    <?php $p=1; ?>
    @foreach($post_types as $post_type)
        <?php 
            $active = ($p==1 and $setup->all_post==null)?"show active":null;
        ?>
        <div class="tab-pane fade {{ $active }} p-2" id="post_type_profile{{ $p }}" role="tabpanel" aria-labelledby="post_type_profile{{ $p }}-tab">
            <?php
            $p++;
            $insite = ($post_type->id == 0)?null:$post_type->id;
            $posts = \App\Post::where('insite',$insite)
                ->where(function ($query) {
                    $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
                })->where('created_at','<',date('Y-m-d H:i:s'))->orderBy('top','DESC')
                ->orderBy('created_at','DESC')
                ->paginate($post_show_number);
            
            foreach($posts as $post){
                if($post->top ==1){
                    if($post->top_date < date('Y-m-d')){
                        $att['top'] = null;
                        $att['top_date'] = null;
                        $post->update($att);
                    }    
                }
            }
            ?>
            @auth
                @can('create',\App\Post::class)
                    <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm mb-2">
                        <i class="fas fa-plus" aria-hidden="true"></i> 新增公告
                    </a>
                @endcan
            @endauth

            <div class="table-responsive">
                <table class="table table-striped" style="word-break:break-all;" aria-label="{{ $post_type->name }} 分類公告列表">
                    <caption class="sr-only">{{ $post_type->name }} 分類公告表格列表</caption>
                    <thead class="thead-light">
                    <tr>
                        <th scope="col" style="width: 120px;">日期</th>
                        <th scope="col" style="width: 100px;">類別</th>
                        <th scope="col" style="min-width: 250px;">標題</th>
                        <th scope="col" style="width: 120px;">發佈者</th>
                        <th scope="col" style="width: 80px;">點閱</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ substr($post->created_at,0,10) }}</td>
                                <td>{{ $post_type->name }}</td>
                                <td>
                                    @if($post->top)
                                        <span class="badge badge-danger">置頂</span>
                                    @endif
                                    @if($post->inbox)
                                        <span class="badge badge-warning">常駐</span>
                                    @endif
                                    <?php
                                    if($post->insite==1){
                                        $can_see = (auth()->check() or check_ip()) ? 1 : 0;
                                    }else{
                                        $can_see = 1;
                                    };
                                    $school_code = school_code();                                    
                                    $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                                    $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                                    ?>
                                    @if($post->insite==1)
                                        <span class="text-danger font-weight-bold">[ 內部公告 ]</span>
                                    @endif

                                    @if($can_see)
                                        <a href="{{ route('posts.show',$post->id) }}" class="font-weight-bold">{{ $post->title }}</a>
                                    @else
                                        <span>{{ $post->title }}</span>
                                    @endif

                                    @if(!empty($photos))
                                        <span class="text-success ml-1">
                                            <i class="fas fa-image" aria-hidden="true"></i>
                                            <span class="sr-only">（含圖片附件）</span>
                                        </span>
                                    @endif
                                    @if(!empty($files))
                                        <span class="text-info ml-1">
                                            <i class="fas fa-download" aria-hidden="true"></i>
                                            <span class="sr-only">（含檔案下載）</span>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('posts.job_title',$post->job_title) }}">{{ $post->job_title }}</a>
                                </td>
                                <td>{{ $post->views }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('posts.type',$post_type->id) }}" class="btn btn-link text-primary btn-sm">
                <i class="far fa-hand-point-up" aria-hidden="true"></i> 查看更多 {{ $post_type->name }}...
            </a>
        </div>
    @endforeach
</div>