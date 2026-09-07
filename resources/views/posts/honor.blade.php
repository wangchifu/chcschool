@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '公告系統：榮譽榜 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：頁籤與分頁 Focus 高對比視覺提示 */
    .nav-tabs .nav-link:focus-visible,
    .pagination .page-link:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-11">
        <!-- 無障礙 HM1010301C 修正：主標題語意化 -->
        <h1 class="h2 mb-3">公告系統：榮譽榜</h1>

        <!-- 無障礙 HM1010301C 修正：補充 nav 導覽區域與 aria-current 屬性 -->
        <nav aria-label="公告分類頁籤">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.index') }}">一般公告</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('posts.honor') }}" aria-current="page">
                        <!-- 無障礙 HM1120201C 修正：裝飾性圖示隱藏與 alt 清空 -->
                        <img src="{{ asset('images/gold-medal.svg') }}" width="16" alt="" aria-hidden="true" class="mr-1">榮譽榜
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.insite') }}">內部公告</a>
                </li>
            </ul>
        </nav>

        <!-- 主要公告列表區塊 -->
        <main aria-label="榮譽榜公告清單">
            @include('posts.list')
        </main>

        <!-- 無障礙 HM1150100C 修正：分頁導覽無障礙包裹 -->
        <nav aria-label="榮譽榜頁碼分頁導覽" class="d-flex justify-content-center mt-3">
            {{ $posts->links() }}
        </nav>
    </div>
</div>
@endsection