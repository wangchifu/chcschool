<style>
    .custom-toggler.navbar-toggler {
        border-color: rgba(255, 255, 255, 0.7);
    }
    .custom-toggler .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255, 0.9)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
    }
    /* 無障礙 HM1020401C 修正：導覽列元素鍵盤 Focus 高對比外框 */
    #mainNav a:focus-visible,
    #mainNav button:focus-visible {
        outline: 3px solid #ffed4a !important;
        outline-offset: 2px !important;
    }
</style>

<!-- 無障礙 HM1110100C 修正：新增 aria-label 識別區域用途 -->
<nav class="navbar navbar-expand-lg {{ $nav_color }}" id="mainNav" aria-label="主導覽選單">
    <div class="container-fluid">
        <!-- 無障礙 HM1240401C 修正：為連結與圖片提供明確的無障礙名稱與替代文字 -->
        <a href="#page-top" class="mr-2" aria-label="回到頁面頂端">
            @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="30" height="30" class="d-inline-block align-top" alt="{{ $setup->site_name }} 圖示">
            @else
                <img src="{{ asset('images/site_logo.png') }}" width="30" height="30" class="d-inline-block align-top" alt="{{ $setup->site_name }} 圖示">
            @endif
        </a>

        <a class="navbar-brand js-scroll-trigger" href="{{ route('index') }}">{{ $setup->site_name }}</a>

        <!-- 無障礙 HM1120201C 修正：將 aria-label 改為中文說明 -->
        <button class="navbar-toggler custom-toggler" 
                type="button" 
                data-toggle="collapse" 
                data-target="#navbarResponsive" 
                aria-controls="navbarResponsive" 
                aria-expanded="false" 
                aria-label="切換主導覽選單選單開關">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
            </ul>
            <ul class="nav navbar-nav navbar-right">
            </ul>
        </div>
    </div>
</nav>