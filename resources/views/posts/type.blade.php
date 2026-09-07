@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', $type_name.' 類別搜尋 | ')

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
        <!-- 無障礙 HM1010301C 修正：主標題語意化 -->
        <h1 class="h2 mb-3">
            {{ $type_name }}
            @auth
            <!--不允許 iframe 
            <a href="{{ route('posts.type_clean',$id) }}" target="_blank" aria-label="在新視窗開啟乾淨版連結"><i class="fas fa-share-square" aria-hidden="true"></i></a>
            -->
            @endauth
        </h1>

        <!-- 麵包屑導覽列 -->
        <nav aria-label="麵包屑導覽">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">公告列表</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $type_name }} 類別搜尋</li>
            </ol>
        </nav>

        <!-- 主要列表區塊 -->
        <main aria-label="{{ $type_name }} 類別公告清單">
            @include('posts.list', ['type_name' => $type_name])
        </main>

        <!-- 無障礙 HM1150100C 修正：分頁導覽區塊無障礙包裹 -->
        <nav aria-label="{{ $type_name }} 類別搜尋結果頁碼分頁導覽" class="d-flex justify-content-center mt-3">
            {{ $posts->links() }}
        </nav>
    </div>
</div>
@endsection