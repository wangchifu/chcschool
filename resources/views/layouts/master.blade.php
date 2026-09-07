<!DOCTYPE html>
<html lang="zh-TW">

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
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('css/bootstrap-navbar.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-5.15.4/css/all.css') }}" rel="stylesheet">
    
    <style>
        /* 無障礙：鍵盤快速跳至主要內容 */
        .sr-only-focusable {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;
        }
        .sr-only-focusable:focus {
            position: fixed; top: 10px; left: 10px; z-index: 9999; width: auto; height: auto;
            padding: 10px 15px; background-color: #000; color: #fff; clip: auto;
            white-space: normal; text-decoration: underline; border-radius: 4px;
        }

        /* 無障礙 WCAG 2.4.7 焦點視覺化指示 */
        a:focus, button:focus, input:focus, select:focus, textarea:focus {
            outline: 3px solid #0056b3 !important;
            outline-offset: 2px !important;
        }

        .navbar-custom {
            background-color: {{ $navbar_custom[0] }};
        }
        /* change the brand and text color */
        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-text {
            color: {{ $navbar_custom[1] }};
        }
        /* change the link color */
        .navbar-custom .navbar-nav .nav-link {
            color: {{ $navbar_custom[2] }};
        }
        /* change the color of active or hovered links */
        .navbar-custom .nav-item.active .nav-link,
        .navbar-custom .nav-item:hover .nav-link {
            color: {{ isset($navbar_custom[3]) ? $navbar_custom[3] : '' }};
        }
    </style>
    @yield('in_head')
</head>

<body id="page-top" style="background-color:{{ $bg_color }};font-family:'Arial','Microsoft JhengHei','微軟正黑體','黑體',sans-serif;">

<!-- 跳過導覽無障礙連結 -->
<a class="sr-only-focusable" href="#main-content" title="跳過主導航頁面，直接跳到主要內容區">跳到主要內容區</a>

@include('layouts.nav')

@yield('top_image')

<br>
{{-- 主要內容區 --}}
<main id="main-content" tabindex="-1" class="container-fluid">
    @yield('content')
</main>
<br>
<br>

<div>
    @yield('footer')
</div>

<script src="{{ asset('js/popper2.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-navbar.js') }}"></script>

@if($setup->fixed_nav)
<link href="{{ asset('css/navbar-top-fixed.css') }}" rel="stylesheet">
@endif
</body>
</html>