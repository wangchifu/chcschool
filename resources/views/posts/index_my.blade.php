@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '我的公告 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：按鈕、頁籤與表格內連結 Focus 高對比視覺提示 */
    .nav-tabs .nav-link:focus-visible,
    .btn:focus-visible,
    .table a:focus-visible,
    .pagination .page-link:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-11">
        <!-- 無障礙 HM1010301C 修正：主標題語意化 -->
        <h1 class="h2 mb-3">
            @if(empty($setup->post_name))
              公告系統
            @else
              {{ $setup->post_name }}
            @endif
        </h1>

        @can('create',\App\Post::class)
        <!-- 無障礙 HM1010301C 修正：補充 nav 區塊與 aria-current 屬性 -->
        <nav aria-label="公告類型切換" class="mb-3">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('posts.index') }}">架上公告</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('posts.index_my') }}" aria-current="page">我的公告</a>
                </li>
            </ul>
        </nav>
        
        <div class="mb-3">
            <!-- 無障礙 HM1120201C 修正：按鈕圖示補充 aria-hidden -->
            <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus" aria-hidden="true"></i> 新增公告
            </a>
        </div>
        @endcan

        <div class="table-responsive">
            <!-- 無障礙 HM1010301C 修正：表格標題與頭部範疇 -->
            <table class="table table-striped" style="word-break:break-all;" aria-label="我的公告列表">
                <caption class="sr-only">我的公告管理與狀態清單</caption>
                <thead class="thead-light">
                <tr>
                    <th scope="col" nowrap style="width: 120px;">日期</th>
                    <th scope="col" nowrap style="width: 100px;">類別</th>
                    <th scope="col" nowrap style="min-width: 250px;">標題</th>
                    <th scope="col" nowrap style="width: 100px;">發佈者</th>
                    <th scope="col" nowrap style="width: 50px;">點閱</th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td scope="row">                                
                            {{ substr($post->created_at,0,10) }}
                            @if($post->created_at > date('Y-m-d H:i:s'))
                                <div class="mt-1"><span class="badge badge-danger">尚未上架<br>{{ $post->created_at }}</span></div>
                            @endif
                            @if($post->die_date < date('Y-m-d') and $post->die_date != null)
                                <div class="mt-1"><span class="badge badge-dark">已經下架<br>{{ $post->die_date  }}</span></div>
                            @endif
                            @if($post->die_date > date('Y-m-d') and $post->die_date != null)
                                <div class="mt-1"><span class="badge badge-warning">下架日期<br>{{ $post->die_date  }}</span></div>
                            @endif
                        </td>
                        <td>
                            @if($post->insite == null)
                                <a href="{{ route('posts.type',0) }}">一般公告</a>
                            @else
                                <a href="{{ route('posts.type',$post->insite) }}">{{ $post_types[$post->insite] }}</a>
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

                            <!-- 無障礙 HM1120201C 修正：圖片與檔案圖示標示可讀性 -->
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

        <!-- 無障礙 HM1150100C 修正：分頁導覽區塊無障礙包裹 -->
        <nav aria-label="我的公告頁碼分頁導覽" class="d-flex justify-content-center mt-3">
            {{ $posts->links() }}
        </nav>
    </div>
</div>
@endsection