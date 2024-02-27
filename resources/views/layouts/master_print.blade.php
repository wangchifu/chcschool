<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <?php
        $school_code = school_code();
        $setup = \App\Setup::find(1);
        $nav_color = (empty($setup->nav_color))?"navbar-dark bg-dark":"navbar-custom";
        $navbar_custom = (empty($setup->nav_color))?['0'=>'','1'=>'','2'=>'','3'=>'']:explode(",",$setup->nav_color);
    ?>
    @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" />
    @else
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('images/site_logo.png') }}" />
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title') | {{ $setup->site_name }}</title>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <!-- icons -->
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bootstrap-4.6.2-dist/css/bootstrap.min.css') }}">
    <link href="{{ asset('fontawesome-5.1.0/css/all.css') }}" rel="stylesheet">
</head>

<body id="page-top" onload='window.print();'>
<div class="container-fluid">
    @yield('content')
</div>
</body>
</html>
