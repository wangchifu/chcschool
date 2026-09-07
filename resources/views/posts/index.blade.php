@extends('layouts.master')

@section('nav_post_active', 'active')

@section('title', '全部公告 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：頁籤與 Focus 焦點高對比視覺提示 */
    .nav-tabs .nav-link:focus-visible,
    .pagination .page-link:focus-visible {
        outline: 3px solid #0056b3 !important;
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