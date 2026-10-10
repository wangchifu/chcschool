<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    <?php
        $school_code = school_code();
        $setup = \App\Setup::first();

        $setup_key = "setup".$school_code;
        if(!session($setup_key)){
            $att['views'] = $setup->views+1;
            $setup->update($att);
        }
        session([$setup_key => '1']);

        $nav_color = (empty($setup->nav_color))?"navbar-dark bg-dark":"navbar-custom";
        $bg_color = (empty($setup->bg_color))?"#f0f1f6":$setup->bg_color;
        $navbar_custom = (empty($setup->nav_color))?['0'=>'','1'=>'','2'=>'','3 me'=>'']:explode(",",$setup->nav_color);
    ?>
    @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" />
    @else
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('images/site_logo.png') }}" />
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $setup->site_name }}全球資訊網">
    <meta name="author" content="">
    <meta http-equiv="Content-Security-Policy" content="script-src * 'unsafe-inline' 'unsafe-eval';">
    <title>@yield('title'){{ $setup->site_name }}</title>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/messages_zh_TW.min.js') }}"></script>
    <!-- icons -->    
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('css/bootstrap-navbar.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-5.15.4/css/all.css') }}" rel="stylesheet">
    
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">
    
    <style>
        /* 動態導覽欄顏色 (來自後台設定，需保留於 Blade 內) */
        .navbar-custom {
            background-color: {{ $navbar_custom[0] }};
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-text {
            color: {{ $navbar_custom[1] }};
        }
        .navbar-custom .navbar-nav .nav-link {
            color: {{ $navbar_custom[2] }};
        }
        .navbar-custom .nav-item.active .nav-link,
        .navbar-custom .nav-item:hover .nav-link {
            color: {{ isset($navbar_custom[3]) ? $navbar_custom[3] : '' }};
        }

        /* =========================================================
           無障礙 2.4.7 焦點可視 (Focus Visible) 完整四邊橘框修正
           ========================================================= */
        
        /* 1. 「跳過導覽連結」焦點獲得時顯眼彈出樣式 */
        .sr-only-focusable {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        .sr-only-focusable:focus {
            position: absolute !important;
            top: 10px !important;
            left: 10px !important;
            z-index: 999999 !important;
            width: auto !important;
            height: auto !important;
            padding: 10px 18px !important;
            margin: 0 !important;
            overflow: visible !important;
            clip: auto !important;
            white-space: normal !important;
            background-color: #0d47a1 !important; /* 高對比深藍背景 */
            color: #ffffff !important;            /* 純白文字 */
            font-weight: bold !important;
            font-size: 1.1rem !important;
            border: 2px solid #ffffff !important;
            border-radius: 6px !important;
            outline: 4px solid #d97706 !important;
            outline-offset: -2px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4) !important;
            text-decoration: none !important;
        }

        /* 2. 全站所有聚焦元素呈現完整四邊高對比橘色焦點框 */
        :focus,
        :focus-visible {
            outline: 3px solid #d97706 !important;
            outline-offset: -3px !important; /* 向內收縮 3px，防止左右邊框被螢幕切掉 */
            box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.3) !important;
        }
    </style>
    @yield('in_head')
</head>

<body id="page-top" style="background-color:{{ $bg_color }};font-family:'Arial','Microsoft JhengHei','微軟正黑體','黑體',sans-serif;">

<!-- 全站統一無障礙跳過導覽 -->
<a class="sr-only-focusable" href="#navbar" accesskey="U" title="頂部導覽區 (Alt+U)">跳到頂部導覽區</a>
<a class="sr-only-focusable" href="#main-content" accesskey="C" title="中央內容區 (Alt+C)">跳到中央內容區</a>

{{-- 無障礙 2.4.1 關鍵修正：僅在當前頁面有宣告 footer 區塊時，才渲染 Alt+Z 連結，避免子頁面無障礙檢測報錯 --}}
@hasSection('footer')
<a class="sr-only-focusable" href="#footer" accesskey="Z" title="底部資訊區 (Alt+Z)">跳到底部資訊區</a>
@endif

{{-- 上方導覽區 --}}
<div id="navbar-container">
    @include('layouts.nav_close')
</div>

@yield('top_image')

<br>
{{-- 主要內容區 --}}
<main id="main-content" tabindex="-1" class="container-fluid">
    @yield('content')
</main>
<br>
<br>

{{-- 下方底部資訊區 (由 index.blade.php 等頁面自行注入) --}}
<div>
    @yield('footer')
</div>

<script src="{{ asset('js/popper2.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-navbar.js') }}"></script>

@if($setup->fixed_nav)
<link href="{{ asset('css/navbar-top-fixed.css') }}" rel="stylesheet">
@endif

<script>
document.addEventListener("DOMContentLoaded", function() {

    /* =========================================================
       全域 AccessKey 無障礙快捷鍵與焦點鎖定 (Prevent default URL hash)
       ========================================================= */

    // 1. 上方主要導覽區 (Alt + U)
    $(document).on('click', 'a[href="#navbar"], a[href="#page-top"], a[accesskey="U"]', function(e) {
        e.preventDefault();
        
        var $nav = $('#navbar');
        if (!$nav.length) {
            $nav = $('.navbar').first();
        }
        if (!$nav.length) {
            $nav = $('#navbar-container');
        }

        if ($nav.length) {
            $nav.attr('tabindex', '-1').focus();
            $('html, body').animate({
                scrollTop: 0
            }, 100);
        }
    });

    // 2. 中央主要內容區 (Alt + C)
    $(document).on('click', 'a[href="#main-content"], a[accesskey="C"]', function(e) {
        e.preventDefault();
        var $main = $('#main-content');
        if ($main.length) {
            $main.attr('tabindex', '-1').focus();
            $('html, body').animate({
                scrollTop: $main.offset().top - 15
            }, 100);
        }
    });

    // 3. 下方底部資訊區 (Alt + Z) - 僅在頁面有 #footer 時作用
    $(document).on('click', 'a[href="#footer"], a[accesskey="Z"]', function(e) {
        e.preventDefault();
        var $footer = $('#footer');
        if (!$footer.length) {
            $footer = $('footer').first();
        }
        if ($footer.length) {
            $footer.attr('tabindex', '-1').focus();
            $('html, body').animate({
                scrollTop: $footer.offset().top - 20
            }, 100);
        }
    });

});
</script>
</body>
</html>