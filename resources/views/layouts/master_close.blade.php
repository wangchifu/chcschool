<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <?php
        $school_code = school_code();
        $setup = \App\Setup::find(1);

        $setup_key = "setup".$school_code;
        if(!session($setup_key)){
            $att['views'] = $setup->views+1;
            $setup->update($att);
        }
        session([$setup_key => '1']);

        $nav_color = (empty($setup->nav_color))?"navbar-dark bg-dark":"navbar-custom";
        $bg_color = (empty($setup->bg_color))?"#f0f1f6":$setup->bg_color;
        $navbar_custom = (empty($setup->nav_color))?['0'=>'','1'=>'','2'=>'','3'=>'']:explode(",",$setup->nav_color);
    ?>
    @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" />
    @else
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('images/site_logo.png') }}" />
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="此網站包含一個專屬的網站標誌（Favicon）。">
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
    <link href="{{ asset('fontawesome-5.1.0/css/all.css') }}" rel="stylesheet">

    <style>
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
            color: {{ $navbar_custom[3] }};
        }

        /* 無障礙 HM1200100C 修正：跳過主內容區塊（Skip Link）樣式 */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #000000;
            color: #ffffff;
            padding: 8px 16px;
            z-index: 9999;
            transition: top 0.2s ease;
            text-decoration: underline;
            font-weight: bold;
        }
        .skip-link:focus {
            top: 0;
        }

        /* 無障礙 HM1020401C 修正：全域鍵盤 Focus 視覺高對比提示 */
        a:focus-visible, 
        button:focus-visible, 
        input:focus-visible, 
        select:focus-visible, 
        textarea:focus-visible {
            outline: 3px solid #0056b3 !important;
            outline-offset: 2px !important;
        }
    </style>
</head>

<body id="page-top" style="background-color:{{ $bg_color }};font-family:'Arial','Microsoft JhengHei','微軟正黑體',sans-serif;">

<!-- 無障礙 HM1200100C 修正：提供跳過主導覽直接前往主要內容的快速連結 -->
<a href="#main-content" class="skip-link">跳過主導覽選單，直接存取主要內容區塊</a>

<!-- 無障礙 HM1110100C 修正：以 header 區塊包覆頂部導覽列 -->
<header>
    @include('layouts.nav_close')
</header>

<!-- 無障礙 HM1110100C 修正：主要內容區使用語義化 <main> 標籤與 role="main" -->
<main id="main-content" class="container-fluid pt-3" tabindex="-1" role="main">
    @yield('content')
</main>

<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>