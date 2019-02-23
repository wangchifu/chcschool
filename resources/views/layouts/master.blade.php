<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <?php $school_code = school_code();?>
    @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" />
    @else
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('images/site_logo.png') }}" />
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title') | {{ env('APP_NAME') }}</title>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <!-- icons -->
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bootstrap-4.1.1/css/bootstrap.min.css') }}">
    <link href="{{ asset('fontawesome-5.1.0/css/all.css') }}" rel="stylesheet">

</head>

<body id="page-top">
@include('layouts.nav')
@yield('top_image')
<br>
<div class="container">
    @yield('content')
</div>
@include('layouts.footer')
</body>
</html>
