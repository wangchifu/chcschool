<?php
    if(!isset($type_name)) $type_name=null;
    $key = rand(100,999);
    session(['search' => $key]);    
    $post_types = \App\PostType::orderBy('order_by')->get();    

    foreach($post_types as $post_type){
      $post_type_array[$post_type->id]=$post_type->name;
    }
?>
<style>
    /* 無障礙 HM1020401C 修正：表單控制項與按鈕 Focus 高對比視覺提示 */
    .form-control:focus-visible,
    .btn:focus-visible,
    .table a:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<!-- 無障礙 HM1010301C 修正：將排版用 table 改為 Flexbox 佈局 -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <!-- 類別選擇與管理按鈕區塊 -->
    <div class="d-flex flex-wrap align-items-center mb-2">
        <form id="select_type_form" action="{{ route('posts.select_type') }}" method="post" class="mr-2 mb-1">
            @csrf
            <!-- 無障礙 HM1150100C 修正：補充 label -->
            <label for="select_type" class="sr-only">選擇公告類別</label>
            <select id="select_type" name="select_type" class="form-control" title="選擇公告類別" aria-label="選擇公告類別">
                <option value="a">請選類別</option>
                @foreach($post_types as $post_type)
                    @if($post_type->disable != 1)
                        <?php $selected = ($post_type->name== $type_name)?"selected":null; ?>
                        <option value="{{ $post_type->id }}" {{ $selected }}>{{ $post_type->name }}</option>
                    @endif
                @endforeach
            </select>
        </form>

        @auth            
            @can('create',\App\Post::class)
                <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm mr-2 mb-1">
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
                <button type="button" class="btn btn-success btn-sm mr-2 mb-1" onclick="alert('{{ $msg }}')">
                    <i class="fas fa-plus" aria-hidden="true"></i> 我想公告
                </button>
            @endcan

            @if(auth()->user()->admin==1)
                <!-- 無障礙 HM1020401C 修正：改用 button 元素開窗 -->
                <button type="button" onclick="open_window('{{ route('posts.show_type') }}','類別管理')" class="btn btn-success btn-sm mb-1" aria-label="開啟類別管理新視窗">
                    <i class="fas fa-cog" aria-hidden="true"></i> 類別管理
                </button>
            @endif
        @endauth
    </div>

    <!-- 關鍵字搜尋區塊 -->
    <div class="mb-2">
        <form action="{{ route('posts.search') }}" method="post" class="form-inline search-form" id="this_form">
            {{ csrf_field() }}
            
            <label for="search" class="sr-only">關鍵字搜尋</label>
            <input type="text" class="form-control form-control-sm mr-1 mb-1" name="search" id="search" title="請輸入要搜尋公告的關鍵字" placeholder="關鍵字" aria-label="請輸入要搜尋公告的關鍵字" required style="width:130px;">

            <label for="check_code" class="sr-only">驗證碼，請輸入 {{ session('search') }}</label>
            <input type="text" class="form-control form-control-sm mr-1 mb-1" name="check" id="check_code" title="請輸入驗證碼" placeholder="驗證碼: {{ session('search') }}" aria-label="請輸入驗證碼 {{ session('search') }}" required maxlength="3" style="width:110px;">

            <button type="submit" class="btn btn-secondary btn-sm mb-1" aria-label="提交搜尋公告">
                <i class="fas fa-search" aria-hidden="true"></i> 搜尋
            </button>
        </form>
    </div>
</div>

@include('layouts.errors')

<div class="table-responsive">
    <!-- 無障礙 HM1010301C 修正：表格標題與頭部範疇 -->
    <table class="table table-striped" style="word-break:break-all;" aria-label="公告列表">
        <caption class="sr-only">公告訊息列表</caption>
        <thead class="thead-light">
        <tr>
            <th scope="col" nowrap style="width: 120px;">日期</th>
            <th scope="col" nowrap style="width: 100px;">類別</th>
            <th scope="col" nowrap style="min-width:250px;">標題</th>
            <th scope="col" nowrap style="width: 100px;">發佈者</th>
            <th scope="col" nowrap style="width: 50px;">點閱</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td scope="row">                    
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
                    //有無附件
                    $files = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/files'));
                    $photos = get_files(storage_path('app/public/'.$school_code.'/posts/'.$post->id.'/photos'));
                    ?>
                    @if($can_see)
                        @if($post->insite==1)
                            <span class="text-danger">[ 內部公告 ]</span>
                        @endif
                        <a href="{{ route('posts.show',$post->id) }}">{{ $post->title }}</a>
                    @else
                        <span class="text-danger">[ 內部公告 ]</span>
                        {{ $post->title }}
                    @endif

                    <!-- 無障礙 HM1120201C 修正：圖片與附件圖示提示 -->
                    @if(!empty($photos))
                        <span class="text-success ml-1" title="包含照片附件" aria-label="包含照片附件">
                            <i class="fas fa-image" aria-hidden="true"></i>
                        </span>
                    @endif
                    @if(!empty($files))
                        <span class="text-info ml-1" title="包含檔案下載" aria-label="包含檔案下載">
                            <i class="fas fa-download" aria-hidden="true"></i>
                        </span>
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
    var validator = $("#this_form").validate();

    function open_window(url, name) {
        window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=900');
    }

    $('#select_type').change(function(){
      if($('#select_type').val() != 'a'){        
        $('#select_type_form').submit();
      }
    });
</script>