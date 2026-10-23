@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '全部公告 | ')

@section('content')
<style>
    /* 無障礙 WCAG 1.4.3 對比度修正：頁籤、分頁與焦點高對比樣式 */
    /* 1. 頁籤連結：非作用中 #0056b3 (7.04:1)，作用中/Focus #003d82 (10.36:1) */
    .nav-tabs .nav-link {
        color: #0056b3 !important;
        font-weight: 600;
    }
    .nav-tabs .nav-link.active {
        color: #003d82 !important;
        font-weight: 700;
        border-bottom: 3px solid #003d82 !important;
    }

    /* 2. 分頁導覽：對比度提升至 7.04:1 (白底) */
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

    /* 無障礙 2.4.7 焦點可見修正 */
    .nav-tabs .nav-link:focus-visible,
    .pagination .page-link:focus-visible {
        outline: 3px solid #003d82 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-11">
        <!-- 無障礙 HM1010301C 修正：語意化頁面主標題 -->
        <h1 class="h2 mb-3">
          @if(empty($setup->post_name))
            公告系統
          @else
            {{ $setup->post_name }}
          @endif
        </h1>

        @can('create',\App\Post::class)
        <!-- 無障礙 HM1010301C 修正：補上 nav 標籤與 aria-label 區分導覽區塊 -->
        <nav aria-label="公告類型切換">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('posts.index') }}" aria-current="page">架上公告</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('posts.index_my') }}">我的公告</a>
                </li>
            </ul>
        </nav>
        @endcan

        <!-- 公告列表模組區塊 -->
        <main aria-label="公告清單">
            @include('posts.list')
        </main>

        <!-- 無障礙 HM1150100C 修正：分頁導覽區塊無障礙包裹 -->
        <nav aria-label="公告頁碼分頁導覽" class="d-flex justify-content-center mt-3">
            {{ $posts->links() }}
        </nav>
    </div>
</div>
@endsection