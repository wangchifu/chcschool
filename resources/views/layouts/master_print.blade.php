<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <?php
        $school_code = school_code();
        $setup = \App\Setup::find(1);
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

    <title>@yield('title') | {{ $setup->site_name }}</title>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <!-- icons -->
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('fontawesome-5.1.0/css/all.css') }}" rel="stylesheet">

    <style>
        /* 無障礙 HM1020401C 修正：鍵盤 Focus 視覺高對比提示 */
        a:focus-visible, 
        button:focus-visible {
            outline: 3px solid #0056b3 !important;
            outline-offset: 2px !important;
        }

        /* 列印專用樣式優化 */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
            }
        }
    </style>
</head>

{{-- 無障礙 HM1220200C 修正：移除 onload="window.print();" 強制彈出，避免中斷螢幕閱讀器操作 --}}
<body id="page-top" style="background-color: {{ $bg_color }}; font-family: 'Arial', 'Microsoft JhengHei', '微軟正黑體', sans-serif;">

<!-- 無障礙友善列印控制區塊（列印時自動隱藏） -->
<div class="container-fluid no-print py-3 bg-light border-bottom">
    <div class="d-flex justify-content-between align-items-center">
        <span class="text-secondary small">若要列印此頁面，請點選右側按鈕或使用鍵盤快捷鍵 (Ctrl + P)</span>
        <button type="button" class="btn btn-primary btn-sm font-weight-bold" onclick="window.print();" aria-label="點擊此處開啟列印功能視窗">
            <i class="fas => print" aria-hidden="true"></i> 列印此頁
        </button>
    </div>
</div>

<!-- 無障礙 HM1110100C 修正：使用語意化 <main> 標籤包覆頁面主要內容 -->
<main id="main-content" class="container-fluid pt-3" role="main" tabindex="-1">
    @yield('content')
</main>

</body>
</html>