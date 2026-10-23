@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '我的公告 | ')

@section('content')
<style>
    /* 無障礙 WCAG 1.4.3 色彩對比度修正 (確保白底對比度 7.04:1 / 灰底對比度 6.29:1) */
    .table a {
        color: #0056b3 !important;
        font-weight: 600;
        text-decoration: none;
    }
    .table a:hover,
    .table a:focus {
        color: #003d82 !important;
        text-decoration: underline !important;
    }

    .nav-tabs .nav-link {
        color: #0056b3 !important;
        font-weight: 600;
    }
    .nav-tabs .nav-link.active {
        color: #003d82 !important;
        font-weight: 700;
        border-bottom: 3px solid #003d82 !important;
    }

    .pagination .page-link {
        color: #0056b3 !important;
        font-weight: 600;
    }
    .pagination .page-item.active .page-link {
        color: #ffffff !important;
        background-color: #0056b3 !important;
        border-color: #0056b3 !important;
    }
    .pagination .page-link:hover,
    .pagination .page-link:focus {
        color: #00224a !important;
        background-color: #e9ecef !important;
    }

    /* 無障礙 2.4.7 焦點可見高對比提示 */
    .nav-tabs .nav-link:focus-visible,
    .btn:focus-visible,
    .table a:focus-visible,
    .pagination .page-link:focus-visible {
        outline: 3px solid #003d82 !important;
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
            <table class="table table-striped align-middle" style="word-break:break-all;" aria-label="我的公告列表">
                <caption class="sr-only">我的公告管理與狀態清單</caption>
                <thead class="thead-light">
                <tr>
                    <th scope="col" class="text-nowrap" style="width: 120px;">日期</th>
                    <th scope="col" class="text-nowrap" style="width: 100px;">類別</th>
                    <th scope="col" class="text-nowrap" style="min-width: 250px;">標題</th>
                    <th scope="col" class="text-nowrap" style="width: 120px;">發佈者</th>
                    <th scope="col" class="text-nowrap text-center" style="width: 60px;">點閱</th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>                                
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
                                <a href="{{ route('posts.type',0) }}" title="查看一般公告">一般公告</a>
                            @else
                                <a href="{{ route('posts.type',$post->insite) }}" title="查看{{ $post_types[$post->insite] }}">{{ $post_types[$post->insite] }}</a>
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
                                    <span class="text-danger font-weight-bold">[ 內部公告 ]</span>
                                @endif
                                <a href="{{ route('posts.show',$post->id) }}" title="閱讀公告：{{ $post->title }}" aria-label="閱讀公告：{{ $post->title }}">{!! a11y_emoji($post->title) !!}</a>
                            @else
                                <span class="text-danger font-weight-bold">[ 內部公告 ]</span>
                                <span class="text-secondary">{{ $post->title }}</span>
                            @endif

                            <!-- 無障礙 HM1120201C 修正：圖片與檔案圖示標示可讀性 -->
                            @if(!empty($photos))
                                <span class="text-success ml-1" title="附有圖片檔">
                                    <i class="fas fa-image" aria-hidden="true"></i>
                                    <span class="sr-only">（附有圖片檔）</span>
                                </span>
                            @endif
                            @if(!empty($files))
                                <span class="text-info ml-1" title="附有附件下載">
                                    <i class="fas fa-download" aria-hidden="true"></i>
                                    <span class="sr-only">（附有附件下載）</span>
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('posts.job_title',$post->job_title) }}" title="查看發佈者 {{ $post->job_title }} 的所有公告" aria-label="查看發佈者 {{ $post->job_title }} 的所有公告">{{ $post->job_title }}</a>
                        </td>
                        <td class="text-center">
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