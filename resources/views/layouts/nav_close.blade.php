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