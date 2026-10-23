<?php $module_setup = get_module_setup(); ?>
<?php
    //$setup = \App\Setup::first();
    $fixed_top = ($setup->fixed_nav)?"fixed-top ":null;
?>

<!-- 全局手機端導覽列排版修補 (獨立於顏色判斷之外) -->
<style>
    @media (max-width: 767.98px) {
        /* 1. 導覽列強制單行不折行 */
        .navbar {
            display: flex !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 0.5rem 0.75rem !important;
        }

        /* 2. 標題與 Logo 區塊：字體自動縮小，過長單行縮略 */
        .navbar-brand {
            font-size: 1.05rem !important; /* 縮小手機端標題字體 */
            font-weight: bold;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important; /* 超過寬度顯示 ... */
            max-width: calc(100% - 60px) !important; /* 預留 60px 給右側漢堡按鈕 */
            margin-right: 0 !important;
            display: flex !important;
            align-items: center !important;
        }

        /* Logo 縮小適應高度 */
        .navbar-brand img {
            width: 30px !important;
            height: 30px !important;
            margin-right: 6px !important;
            flex-shrink: 0;
        }

        /* 3. 漢堡選單按鈕美化 (去除舊式灰色立體邊框與陰影) */
        .navbar-toggler {
            padding: 5px 10px !important;
            font-size: 1.25rem !important;
            background-color: rgba(255, 255, 255, 0.2) !important; /* 半透明質感扁平底色 */
            border: 1px solid rgba(255, 255, 255, 0.4) !important;  /* 微光柔和外框 */
            border-radius: 6px !important;
            color: inherit !important; /* 繼承選單文字顏色 */
            box-shadow: none !important;
            outline: none !important;
            flex-shrink: 0 !important; /* 強迫按鈕不被壓扁 */
        }

        /* 按鈕點擊或獲得焦點時的提示框 */
        .navbar-toggler:focus, .navbar-toggler:hover {
            background-color: rgba(255, 255, 255, 0.35) !important;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5) !important;
        }
    }
</style>

@if($nav_color == 'navbar-custom' && isset($navbar_custom[0]))
<style>
    .navbar-custom {
        /* $navbar_custom[0]: Navbar 底色 */
        background-color: {{ $navbar_custom[0] }} !important;
        
        /* $navbar_custom[1]: 網站名稱 / 文字顏色 */
        --nav-brand-color: {{ $navbar_custom[1] }};
        
        /* $navbar_custom[2]: 連結文字顏色 */
        --nav-link-color: {{ $navbar_custom[2] }};
        
        /* $navbar_custom[3]: Hover / Active 文字顏色 */
        --nav-hover-color: {{ $navbar_custom[3] }};
    }
</style>
@endif

<nav class="navbar navbar-expand-lg {{ $nav_color }} {{ $fixed_top }}" id="mainNav" role="navigation" aria-label="主要選單導覽">
    <div class="container-fluid">        
        {{-- 無障礙 1.1.1 修正：提供機關標誌完整 alt 說明，並將相鄰重複文字設為 aria-hidden --}}
        <a class="navbar-brand js-scroll-trigger d-inline-flex align-items-center" href="{{ route('index') }}" title="返回首頁 - {{ $setup->site_name }}">
            @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="30" height="30" class="mr-2" alt="{{ $setup->site_name }}標誌">
            @else
                <img src="{{ asset('images/site_logo.png') }}" width="30" height="30" class="mr-2" alt="{{ $setup->site_name }}標誌">
            @endif
            <span style="white-space: nowrap;" aria-hidden="true">{{ $setup->site_name }}</span>
        </a>        
        
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="切換導覽選單顯示">
            <span class="navbar-toggler-icon" aria-hidden="true"></span>
        </button>
                
    </div>
</nav>