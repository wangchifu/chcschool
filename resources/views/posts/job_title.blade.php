@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', $job_title.' 職稱搜尋 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：麵包屑與分頁 Focus 高對比視覺提示 */
    .breadcrumb a:focus-visible,
    .pagination .page-link:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-11">
        <!-- 無障礙 HM1010301C 修正：語意化頁面主標題 -->
        <h1 class="h2 mb-3">{{ $job_title }} 公告</h1>

        <!-- 麵包屑導覽列 -->
        <nav aria-label="麵包屑導覽">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">公告列表</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $job_title }} 職稱搜尋</li>
            </ol>
        </nav>

        <!-- 主要列表區塊 -->
        <main aria-label="{{ $job_title }} 的公告清單">
            @include('posts.list')
        </main>

        <!-- 無障礙 HM1150100C 修正：僅在有分頁資料時才輸出 nav，避免空區塊報錯 -->
        @if($posts->hasPages())
            <nav aria-label="{{ $job_title }} 搜尋結果頁碼分頁導覽" class="d-flex justify-content-center mt-3">
                {{ $posts->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection