<ul class="nav nav-tabs" id="myTab" role="tablist">
    
    @if($setup->all_post)
    <li class="nav-item">
        <a class="nav-link active" id="post_type_all_post-tab" data-toggle="tab" href="#post_type_all_post" role="tab" aria-controls="post_type_all_post" aria-selected="true">全部公告</a>
    </li>
    @endif
    <?php
    $p=1;
    ?>
    @foreach($post_types as $post_type)
        <?php
        $active = ($p==1 and $setup->all_post==null)?"active":null;  
        $aria_selected = ($p==1 and $setup->all_post==null)?"true":"false";  
        ?>
        <li class="nav-item">
            <a class="nav-link {{ $active }}" id="post_type_profile{{ $p }}-tab" data-toggle="tab" href="#post_type_profile{{ $p }}" role="tab" aria-controls="post_type_profile{{ $p }}" aria-selected="{{ $aria_selected }}">{{ $post_type->name }}</a>
        </li>
        <?php $p++; ?>
    @endforeach
</ul>
<div class="tab-content" id="myTabContent">
    @if ($setup->all_post==1)
        <div class="tab-pane fade show active" id="post_type_all_post" role="tabpanel" aria-labelledby="post_type_all_post-tab" style="margin: 10px;">
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
                    <th>
                        類別
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
                                @if($post->inbox)
                                    <p class="badge badge-warning">常駐</p>
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
            <a href="{{ route('posts.index') }}"><small><i class="far fa-hand-point-up"></i> 更多 公告...</small></a>
        </div>
        @endif
    <?php
    $p=1;
    ?>
    @foreach($post_types as $post_type)
        <?php 
            $active = ($p==1 and $setup->all_post==null)?"show active":null;
        ?>
        <div class="tab-pane fade {{ $active }}" id="post_type_profile{{ $p }}" role="tabpanel" aria-labelledby="post_type_profile{{ $p }}-tab" style="margin: 10px;">
            <?php
            $p++;
            $insite = ($post_type->id == 0)?null:$post_type->id;
            $posts = \App\Post::where('insite',$insite)
                ->where(function ($query) {
                    $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
                })->where('created_at','<',date('Y-m-d H:i:s'))->orderBy('top','DESC')
                ->orderBy('created_at','DESC')
                ->paginate($post_show_number);
            //檢查置頂日期
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
                    <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
                @endcan
            @endauth
            <table class="table table-striped rwd-table" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th nowrap width="160">
                        日期
                    </th>
                    <th>
                        類別
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
                                @if($post->inbox)
                                    <p class="badge badge-warning">常駐</p>
                                @endif
                                {{ substr($post->created_at,0,10) }}
                            </td>
                            <td data-th="類別">
                                {{ $post_type->name }}
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
            <a href="{{ route('posts.type',$post_type->id) }}"><small><i class="far fa-hand-point-up"></i> 更多 {{ $post_type->name }}...</small></a>
        </div>
    @endforeach
</div>
