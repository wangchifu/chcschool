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
</head>

<body id="page-top" style="background-color:{{ $bg_color }};font-family:'Arial','Microsoft YaHei','黑體','宋體',sans-serif;">
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
</style>
@include('layouts.nav_close')
<br>
<div class="container-fluid">
    @yield('content')
    <br>
    <br>
</div>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
</body>
</html>
