<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('images/site_logo.png') }}" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title') | {{ env('APP_NAME') }}</title>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <!-- icons -->
    <link rel="stylesheet" href="{{ asset('bootstrap-4.1.1/css/bootstrap.min.css') }}" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link href="{{ asset('fontawesome-5.1.0/css/all.css') }}" rel="stylesheet">

</head>

<body id="page-top">
@include('layouts.nav')
@yield('top_image')
<br>
<div class="container-fluid">
    @yield('content')
</div>
@include('layouts.footer')


</body>
</html>
