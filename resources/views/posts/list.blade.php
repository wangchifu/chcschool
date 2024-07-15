<?php
    if(!isset($type_name)) $type_name=null;
    $key = rand(100,999);
    session(['search' => $key]);
    $post_type_array['a'] = "請選類別";
   	$post_type_array[0] = "一般公告";  
	$post_types = \App\PostType::where('disable',null)->orderBy('order_by')->pluck('name','id')->toArray();	

	foreach($post_types as $k=>$v){
	  $post_type_array[$k]=$v;
	}
?>
<div style="float:left">
    <table>
        <tr>
            <td>
                <form id="select_type_form" action='{{ route('posts.select_type') }}' method='post'>
                    @csrf                    
                    <select id="select_type" name="select_type" class="form-control">
                        @foreach($post_type_array as $k=>$v)
                            <?php $selected = ($v== $type_name)?"selected":null; ?>
                            <option value='{{$k}}' {{ $selected }}>{{$v}}</option>
                        @endforeach
                </select>
                </form>
            </td>
        </tr>
    </table>
</div>
<div style="float: right">
    <form action="{{ route('posts.search') }}" method="post" class="search-form" id="this_form">
        {{ csrf_field() }}
        <table>
            <tr>
                @auth
                    @if(auth()->user()->admin==1)
                        <td>
                            <a href="javascript:open_window('{{ route('posts.show_type') }}','新視窗')" class="btn btn-success btn-sm"><i class="fas fa-cog"></i> 類別管理</a>
                        </td>
                    @endif
                @endauth                
                <td>
                    <input type="text" class="form-control" name="search" id="search" placeholder="搜尋關鍵字" required>
                </td>
                <td>
                    <input type="text" class="form-control" name="check" placeholder="請輸入：{{ session('search') }}" required maxlength="3">
                </td>
                <td>
                    <button class="btn btn-secondary"><i class="fas fa-search"></i></button>
                </td>
            </tr>
        </table>
        @include('layouts.errors')
    </form>
</div>
<table class="table table-striped rwd-table" style="word-break:break-all;">
    <thead class="thead-light">
    <tr>
        <th nowrap width="200px">日期
            @can('create',\App\Post::class)
                <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增公告</a>
            @endauth
	    </th>
	    <th width="100px">
            類別
        </th>
        <th nowrap>
            標題
        </th>
        <th nowrap>發佈者</th>
        <th nowrap>點閱</th>
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
                @if($post->insite == null)
                    <a href="{{ route('posts.type',0) }}">一般公告</a>
                @else
                    <a href="{{ route('posts.type',$post->insite) }}">{{ $post_types[$post->insite] }}</a>
                @endif
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
<script>
    var validator = $("#this_form").validate();


    function open_window(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=800');
    }
    $('#select_type').change(function(){
      if($('#select_typye').val() != 'a'){
        $('#select_type_form').submit();
      }
    });
</script>
