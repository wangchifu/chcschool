<?php
    if(!isset($type_name)) $type_name=null;
    $key = rand(100,999);
    session(['search' => $key]);    
	$post_types = \App\PostType::orderBy('order_by')->get();	

	foreach($post_types as $post_type){
	  $post_type_array[$post_type->id]=$post_type->name;
	}
?>
<div style="float:left">
    <table>
        <tr>
            <td>
                <form id="select_type_form" action='{{ route('posts.select_type') }}' method='post'>
                    @csrf                    
                    <select id="select_type" name="select_type" class="form-control">
                        <option value="a">請選類別</option>
                        @foreach($post_types as $post_type)
                            @if($post_type->disable != 1)
                                <?php $selected = ($post_type->name== $type_name)?"selected":null; ?>
                                <option value='{{ $post_type->id }}' {{ $selected }}>{{ $post_type->name }}</option>
                            @endif
                        @endforeach
                </select>
                </form>                
            </td>            
            @can('create',\App\Post::class)
                <td>
                    <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
                </td>
            @endauth            
            @auth
                @if(auth()->user()->admin==1)
                    <td>
                        <a href="javascript:open_window('{{ route('posts.show_type') }}','新視窗')" class="btn btn-success btn-sm"><i class="fas fa-cog"></i> 類別管理</a>
                    </td>
                @endif
            @endauth
        </tr>
    </table>
</div>
<div style="float: right">
    <form action="{{ route('posts.search') }}" method="post" class="search-form" id="this_form">
        {{ csrf_field() }}
        <table>
            <tr>                         
                <td>
                    <input type="text" class="form-control" name="search" id="search" title="請輸入要搜尋公告的關鍵字" placeholder="關鍵字" required style="width:120px;">
                </td>
                <td>
                    <input type="text" class="form-control" name="check" title="請輸入驗證碼" placeholder="{{ session('search') }}" required maxlength="3" style="width:100px;">
                </td>
                <td>
                    <button class="btn btn-secondary btn-sm" aria-label="提交搜尋公告的表單"><i class="fas fa-search"></i></button>
                </td>                
                <td>
                    <a href="{{ route('rss') }}" title="連結到RSS畫面" target="_blank"><i class="fas fa-rss-square h2" style="color:#FF9224"></i></a></td>           
                <td>
            </tr>
        </table>
        @include('layouts.errors')
    </form>
</div>
<div class="table-responsive">
    <table class="table table-striped" style="word-break:break-all;">
        <thead class="thead-light">
        <tr>
            <th nowrap width="120px">
                日期            
            </th>
            <th nowrap width="100px">
                類別
            </th>
            <th nowrap style="min-width:250px;">
                標題
            </th>
            <th nowrap width="100px">發佈者</th>
            <th nowrap width="50px">點閱</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>                    
                    {{ substr($post->created_at,0,10) }}
                </td>
                <td>
                    @if($post->insite == null)
                        <a href="{{ route('posts.type',0) }}">一般公告</a>
                    @else
                        <a href="{{ route('posts.type',$post->insite) }}">{{ $post_type_array[$post->insite] }}</a>
                    @endif
                </td>
                <td>
                    @if($post->top)
                        <p class="badge badge-danger">置頂</p>
                    @endif
                    @if($post->inbox)
                        <p class="badge badge-warning">常駐</p>
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
                    @if($can_see)
                        @if($post->insite==1)
                            <span class="text-danger">[ 內部公告 ]</span>
                        @endif
                        <a href="{{ route('posts.show',$post->id) }}">{{ $title }}</a>
                    @else
                <span class='text-danger'>[ 內部公告 ]</span>
                        {{ $title  }}
                    @endif
                    @if(!empty($photos))
                        <span class="text-success"><i class="fas fa-image"></i></span>
                    @endif
                    @if(!empty($files))
                        <span class="text-info"> <i class="fas fa-download"></i></span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('posts.job_title',$post->job_title) }}">{{ $post->job_title }}</a>
                </td>
                <td>
                    {{ $post->views }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script>
    var validator = $("#this_form").validate();


    function open_window(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=900');
    }
    $('#select_type').change(function(){
      if($('#select_type').val() != 'a'){        
        $('#select_type_form').submit();
      }
    });
</script>
