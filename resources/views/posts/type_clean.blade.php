@extends('layouts.master_clean')

@section('nav_post_active', 'active')

@section('title', '類別搜尋 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>{{ $type_name }}</h1>
<table class="table table-striped rwd-table" style="word-break:break-all;">
    <thead class="thead-light">
    <tr>
        <th nowrap>日期
	    </th>
        <?php
        $post_type_array['a'] = "類別";
   	$post_type_array[0] = "一般公告";  
	$post_types = \App\PostType::orderBy('order_by')->pluck('name','id')->toArray();	

	foreach($post_types as $k=>$v){
	  $post_type_array[$k]=$v;
	}
        ?>
	<th>類別
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
                    一般公告
                @else
                    {{ $post_types[$post->insite] }}
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
                //有無附件
                $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                ?>
                @if($can_see)
                    @if($post->insite==1)
                        <span class="text-danger">[ 內部公告 ]</span>
                    @endif
                    <a href="{{ route('posts.show_clean',$post->id) }}">{{ $post->title }}</a>
                @else
		    <span class='text-danger'>[ 內部公告 ]</span>
                    {{ $post->title }}
                @endif
                @if(!empty($photos))
                    <span class="text-success"><i class="fas fa-image"></i></span>
                @endif
                @if(!empty($files))
                    <span class="text-info"> <i class="fas fa-download"></i></span>
                @endif
            </td>
            <td data-th="發佈者">
                {{ $post->job_title }}
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

            {{ $posts->links() }}
        </div>
    </div>
@endsection
