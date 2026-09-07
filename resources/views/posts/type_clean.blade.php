@extends('layouts.master_clean')

@section('nav_post_active', 'active')

@section('title', $type_name.' 類別搜尋 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：公告連結與分頁 Focus 高對比視覺提示 */
    table a:focus-visible,
    .pagination .page-link:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="row justify-content-center">
    <main class="col-md-11" aria-label="類別搜尋公告結果">
        <!-- 頁面主標題 -->
        <h1 class="h2 mb-4">{{ $type_name }}</h1>

        <!-- 無障礙 HM1010301C 修正：語意化資料表格 -->
        <div class="table-responsive">
            <table class="table table-striped rwd-table" style="word-break:break-all;" aria-label="{{ $type_name }} 類別公告清單">
                <caption class="sr-only">{{ $type_name }} 類別下的公告清單列表，包含日期、類別、標題、發佈者與點閱數資訊</caption>
                <thead class="thead-light">
                <tr>
                    <th scope="col" style="white-space: nowrap;">日期</th>
                    <?php
                        $post_type_array['a'] = "類別";
                        $post_type_array[0] = "一般公告";  
                        $post_types = \App\PostType::orderBy('order_by')->pluck('name','id')->toArray();    

                        foreach($post_types as $k=>$v){
                            $post_type_array[$k]=$v;
                        }
                    ?>
                    <th scope="col" style="white-space: nowrap;">類別</th>
                    <th scope="col" style="white-space: nowrap;">標題</th>
                    <th scope="col" style="white-space: nowrap;">發佈者</th>
                    <th scope="col" style="white-space: nowrap;">點閱</th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td data-th="日期">
                            @if($post->top)
                                <span class="badge badge-danger">置頂</span>
                            @endif
                            @if($post->inbox)
                                <span class="badge badge-warning">常駐</span>
                            @endif
                            {{ substr($post->created_at,0,10) }}
                        </td>
                        <td data-th="類別">
                            @if($post->insite == null)
                                一般公告
                            @else
                                {{ $post_types[$post->insite] ?? '一般公告' }}
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
                                    <span class="text-danger font-weight-bold">[ 內部公告 ]</span>
                                @endif
                                <a href="{{ route('posts.show_clean',$post->id) }}">{{ $post->title }}</a>
                            @else
                                <span class="text-danger font-weight-bold">[ 內部公告 ]</span>
                                {{ $post->title }}
                            @endif

                            <!-- 無障礙 HM1120201C 修正：附件圖示隱藏與補上文字說明 -->
                            @if(!empty($photos))
                                <span class="text-success ml-1" title="包含照片附件">
                                    <i class="fas fa-image" aria-hidden="true"></i>
                                    <span class="sr-only">(含照片附件)</span>
                                </span>
                            @endif
                            @if(!empty($files))
                                <span class="text-info ml-1" title="包含檔案附件">
                                    <i class="fas fa-download" aria-hidden="true"></i>
                                    <span class="sr-only">(含檔案附件)</span>
                                </span>
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
        </div>

        <!-- 無障礙 HM1150100C 修正：分頁導覽區塊無障礙包裹 -->
        <nav aria-label="{{ $type_name }} 類別公告頁碼分頁導覽" class="d-flex justify-content-center mt-3">
            {{ $posts->links() }}
        </nav>
    </main>
</div>

<script>
    var validator = $("#this_form").validate();

    function open_window(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=800');
    }
    $('#select_type').change(function(){
      if($('#select_type').val() != 'a'){
        $('#select_type_form').submit();
      }
    });
</script>
@endsection